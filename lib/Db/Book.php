<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//
declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;
use ReturnTypeWillChange;

/**
 * @method string getUserid()
 * @method void setUserid(string $userid)
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method string getAuthor()
 * @method void setAuthor(string $author)
 * @method int getPosition()
 * @method void setPosition(int $position)
 * @method string getUrl()
 * @method void setUrl(string $url)
 * @method int getFile()
 * @method void setFile(int $file)
 * @method string getColour()
 * @method void setColour(string $colour)
 * @method int getPattern()
 * @method void setPattern(int $pattern)
 * @method int getHeight()
 * @method void setHeight(int $height)
 */
class Book extends Entity implements JsonSerializable {
	protected ?string $userid = null;
	protected ?string $title = null;
	protected ?string $author = null;
	protected ?int $position = null;
	protected ?string $url = null;
	protected ?int $file = null;
	protected ?string $colour = null;
	protected ?int $pattern = null;
	protected ?int $height = null;

	public function __construct() {
		$this->addType('userid', 'string');
		$this->addType('title', 'string');
		$this->addType('author', 'string');
		$this->addType('position', 'int');
		$this->addType('url', 'string');
		$this->addType('file', 'int');
		$this->addType('colour', 'string');
		$this->addType('pattern', 'int');
		$this->addType('height', 'int');
	}

	#[ReturnTypeWillChange]
	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'userid' => $this->userid,
			'title' => $this->title,
			'author' => $this->author,
			'position' => $this->position,
			'url' => $this->url,
			'file' => $this->file,
			'colour' => $this->colour,
			'pattern' => $this->pattern,
			'height' => $this->height,
		];
	}
}
