<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//

declare(strict_types=1);

namespace OCA\Bookshelfs\Service;

use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IPreview;
use Throwable;

/**
 * @psalm-suppress MissingDependency
 */
class EbookFileService {
	private const COVER_DIR = 'Bookshelfs/covers';

	private const AVAILABLE_COLOURS = [
		'maroon',
		'darkgreen',
		'darkolivegreen',
		'brown',
		'saddlebrown',
		'sienna',
		'midnightblue',
	];

	public function __construct(
		private readonly IRootFolder $rootFolder,
		private readonly IPreview $preview,
	) {
	}

	/**
	 * Extract metadata and cover from an e-book file and persist the cover.
	 *
	 * @return array{title: string, author: string, url: string, file: int}
	 * @throws NotFoundException
	 */
	public function extractBook(int $fileId, string $userId): array {
		$file = $this->getFile($fileId, $userId);
		$userFolder = $this->rootFolder->getUserFolder($userId);
		$extension = strtolower(pathinfo($file->getName(), PATHINFO_EXTENSION));
		$fallbackTitle = pathinfo($file->getName(), PATHINFO_FILENAME);

		$title = '';
		$author = '';
		$coverFileId = null;

		if ($extension === 'epub') {
			$extracted = $this->extractEpub($file);
			$title = $extracted['title'];
			$author = $extracted['author'];
			if ($extracted['cover'] !== null) {
				$coverFileId = $this->saveCoverBytes($userFolder, $extracted['cover']);
			}
		} elseif ($extension === 'pdf') {
			[$pdfTitle, $pdfAuthor] = $this->extractPdfMeta($file->getContent());
			$title = $pdfTitle;
			$author = $pdfAuthor;
			$coverFileId = $this->savePreviewCover($userFolder, $file);
		}

		if ($title === '') {
			$title = $fallbackTitle;
		}

		return [
			'title' => $title,
			'author' => $this->truncateAuthor($author),
			'url' => $coverFileId !== null ? (string)$coverFileId : '-1',
			'file' => $fileId,
		];
	}

	public static function randomColour(): string {
		return self::AVAILABLE_COLOURS[array_rand(self::AVAILABLE_COLOURS)];
	}

	public static function randomPattern(): int {
		return random_int(0, 3);
	}

	public static function randomHeight(int $min = 220, int $max = 290): int {
		return random_int($min, $max);
	}

	/**
	 * @return array{title: string, author: string, cover: string|null}
	 */
	private function extractEpub(File $file): array {
		$tmp = tempnam(sys_get_temp_dir(), 'epub');
		if ($tmp === false) {
			return ['title' => '', 'author' => '', 'cover' => null];
		}

		$in = $file->fopen('r');
		$out = fopen($tmp, 'w');
		if ($in === false || $out === false) {
			$this->closeHandles($in, $out);
			@unlink($tmp);
			return ['title' => '', 'author' => '', 'cover' => null];
		}
		stream_copy_to_stream($in, $out);
		$this->closeHandles($in, $out);

		$result = ['title' => '', 'author' => '', 'cover' => null];
		$zip = new \ZipArchive();
		if ($zip->open($tmp) !== true) {
			@unlink($tmp);
			return $result;
		}

		$container = $zip->getFromName('META-INF/container.xml');
		$opfPath = $this->getOpfPath($container);
		if ($opfPath === null) {
			$zip->close();
			@unlink($tmp);
			return $result;
		}

		$opf = $zip->getFromName($opfPath);
		$parsed = $this->parseOpf($opf);
		$result['title'] = $parsed['title'];
		$result['author'] = $parsed['author'];

		if ($parsed['coverHref'] !== null) {
			$coverPath = $this->resolveZipPath($opfPath, $parsed['coverHref']);
			$cover = $zip->getFromName($coverPath);
			if (is_string($cover) && $cover !== '') {
				$result['cover'] = $cover;
			}
		}

		$zip->close();
		@unlink($tmp);
		return $result;
	}

	private function getOpfPath(string|false $container): ?string {
		if ($container === false) {
			return null;
		}

		$doc = new \DOMDocument();
		if ($doc->loadXML($container) === false) {
			return null;
		}

		$xpath = new \DOMXPath($doc);
		$xpath->registerNamespace('c', 'urn:oasis:names:tc:opendocument:xmlns:container');
		$rootfile = $xpath->query('//c:rootfiles/c:rootfile')->item(0);
		if (!$rootfile instanceof \DOMElement) {
			return null;
		}

		$path = $rootfile->getAttribute('full-path');
		return $path !== '' ? $path : null;
	}

	/**
	 * @return array{title: string, author: string, coverHref: string|null}
	 */
	private function parseOpf(string|false $opf): array {
		$result = ['title' => '', 'author' => '', 'coverHref' => null];
		if ($opf === false) {
			return $result;
		}

		$doc = new \DOMDocument();
		if ($doc->loadXML($opf) === false) {
			return $result;
		}

		$xpath = new \DOMXPath($doc);
		$xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');
		$xpath->registerNamespace('opf', 'http://www.idpf.org/2007/opf');

		$title = $xpath->query('//dc:title')->item(0);
		$creator = $xpath->query('//dc:creator')->item(0);
		$result['title'] = $title?->textContent ?? '';
		$result['author'] = $creator?->textContent ?? '';

		$coverId = null;
		foreach ($xpath->query('//opf:metadata/opf:meta') ?: [] as $meta) {
			if (!$meta instanceof \DOMElement) {
				continue;
			}
			// EPUB2: <meta name="cover" content="cover-image-id"/>
			if ($meta->getAttribute('name') === 'cover') {
				$coverId = $meta->getAttribute('content');
				break;
			}
			// EPUB3: <meta property="cover-image" content="cover-image-id"/>
			if ($meta->getAttribute('property') === 'cover-image') {
				$coverId = $meta->getAttribute('content');
				break;
			}
		}

		foreach ($xpath->query('//opf:manifest/opf:item') ?: [] as $item) {
			if (!$item instanceof \DOMElement) {
				continue;
			}
			$isCoverById = $coverId !== null && $coverId !== '' && $item->getAttribute('id') === $coverId;
			$isCoverByProperty = str_contains($item->getAttribute('properties'), 'cover-image');
			if ($isCoverById || $isCoverByProperty) {
				$href = $item->getAttribute('href');
				$result['coverHref'] = $href !== '' ? $href : null;
				break;
			}
		}

		return $result;
	}

	private function resolveZipPath(string $opfPath, string $href): string {
		$href = str_replace('\\', '/', $href);
		$baseDir = dirname($opfPath);
		$baseDir = $baseDir === '.' ? '' : $baseDir . '/';

		$parts = explode('/', $baseDir . $href);
		$stack = [];
		foreach ($parts as $part) {
			if ($part === '' || $part === '.') {
				continue;
			}
			if ($part === '..') {
				if ($stack !== []) {
					array_pop($stack);
				}
				continue;
			}
			$stack[] = $part;
		}

		return implode('/', $stack);
	}

	/**
	 * @return array{0: string, 1: string}
	 */
	private function extractPdfMeta(string $content): array {
		return [
			$this->extractPdfStringField($content, 'Title'),
			$this->extractPdfStringField($content, 'Author'),
		];
	}

	private function extractPdfStringField(string $content, string $field): string {
		$escapedField = preg_quote($field, '/');
		// Match `/Field <hex>` (hex string).
		if (preg_match('/\/' . $escapedField . '\s*<([0-9A-Fa-f]+)>/', $content, $matches) === 1) {
			return $this->decodePdfHexString($matches[1]);
		}

		// Match `/Field (literal...)` (literal string), taking escapes into account.
		if (preg_match('/\/' . $escapedField . '\s*\(/', $content, $matches, PREG_OFFSET_CAPTURE) === 1) {
			$start = $matches[0][1] + strlen($matches[0][0]);
			$value = $this->readPdfLiteralString($content, $start);
			if ($value !== null) {
				return $value;
			}
		}

		return '';
	}

	/**
	 * Read a PDF literal string starting right after the opening `(`, honouring
	 * escaped characters (`\(`, `\)`, `\\`) and nested parentheses.
	 */
	private function readPdfLiteralString(string $content, int $start): ?string {
		$len = strlen($content);
		$out = '';
		$depth = 1;
		for ($i = $start; $i < $len; $i++) {
			$c = $content[$i];
			if ($c === '\\') {
				if ($i + 1 >= $len) {
					break;
				}
				$next = $content[++$i];
				$out .= match ($next) {
					'n' => "\n",
					'r' => "\r",
					't' => "\t",
					'b' => "\b",
					'f' => "\f",
					'(' => '(',
					')' => ')',
					'\\' => '\\',
					default => (ctype_digit($next))
						? chr((int)$this->octalEscape($content, $i))
						: $next,
				};
				continue;
			}
			if ($c === '(') {
				$depth++;
				$out .= $c;
				continue;
			}
			if ($c === ')') {
				$depth--;
				if ($depth === 0) {
					return $out;
				}
				$out .= $c;
				continue;
			}
			$out .= $c;
		}
		return null;
	}

	private function octalEscape(string $content, int &$index): string {
		$digits = '';
		while (strlen($digits) < 3 && $index + 1 < strlen($content) && ctype_digit($content[$index + 1])) {
			$digits .= $content[++$index];
		}
		return $digits === '' ? '0' : $digits;
	}

	private function decodePdfHexString(string $hex): string {
		$bytes = hex2bin($hex);
		if ($bytes === false || $bytes === '') {
			return '';
		}

		// PDF info strings are UTF-16BE when prefixed with a BOM.
		if (str_starts_with($bytes, "\xFE\xFF")) {
			$decoded = mb_convert_encoding(substr($bytes, 2), 'UTF-8', 'UTF-16BE');
			return $decoded === false ? '' : $decoded;
		}

		// Without a BOM, PDF metadata is typically Latin-1 (ISO-8859-1);
		// convert it so the resulting string is valid UTF-8.
		return mb_convert_encoding($bytes, 'UTF-8', 'ISO-8859-1');
	}

	private function savePreviewCover(Folder $userFolder, File $file): ?int {
		try {
			if (!$this->preview->isAvailable($file)) {
				return null;
			}
			$preview = $this->preview->getPreview($file, 190, 280, false, IPreview::MODE_FILL);
			return $this->writeCover($userFolder, $preview->getContent(), 'jpg');
		} catch (Throwable) {
			return null;
		}
	}

	private function saveCoverBytes(Folder $userFolder, string $bytes): ?int {
		$extension = $this->normaliseCoverBytes($bytes);
		if ($extension === null) {
			return null;
		}
		return $this->writeCover($userFolder, $bytes, $extension);
	}

	private function writeCover(Folder $userFolder, string $bytes, string $extension): ?int {
		try {
			$coverFolder = $this->ensureFolder($userFolder, self::COVER_DIR);
			$name = bin2hex(random_bytes(8)) . '.' . $extension;
			$node = $coverFolder->newFile($name);
			$node->putContent($bytes);
			return $node->getId();
		} catch (Throwable) {
			return null;
		}
	}

	private function ensureFolder(Folder $folder, string $path): Folder {
		foreach (explode('/', $path) as $segment) {
			if ($segment === '') {
				continue;
			}
			if (!$folder->nodeExists($segment)) {
				$folder = $folder->newFolder($segment);
				continue;
			}
			$node = $folder->get($segment);
			if ($node instanceof Folder) {
				$folder = $node;
			}
		}
		return $folder;
	}

	private function normaliseCoverBytes(string $bytes): ?string {
		$info = @getimagesizefromstring($bytes);
		if ($info === false) {
			return null;
		}

		$mime = $info['mime'] ?? '';
		return match ($mime) {
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
			'image/gif' => 'gif',
			'image/webp' => 'webp',
			default => null,
		};
	}

	private function getFile(int $fileId, string $userId): File {
		$userFolder = $this->rootFolder->getUserFolder($userId);
		foreach ($userFolder->getById($fileId) as $node) {
			if ($node instanceof File) {
				return $node;
			}
		}
		throw new NotFoundException('Book file not found');
	}

	private function truncateAuthor(string $author): string {
		$author = trim($author);
		if ($author === '') {
			return '';
		}
		return mb_substr($author, 0, 4);
	}

	private function closeHandles($in, $out): void {
		if (is_resource($in)) {
			fclose($in);
		}
		if (is_resource($out)) {
			fclose($out);
		}
	}
}
