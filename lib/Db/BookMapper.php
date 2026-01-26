<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//
declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;

use OCP\IDBConnection;

/**
 * @extends QBMapper<Book>
 */
class BookMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'bookshelfs', Book::class);
	}

	/**
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function getBookOfUser(int $id, string $userid): Book {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			)
			->andWhere(
				$qb->expr()->eq('userid', $qb->createNamedParameter($userid))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @return Book[]
	 * @throws Exception
	 */
	public function getBooksOfUser(string $userid): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('userid', $qb->createNamedParameter($userid))
			);

		return $this->findEntities($qb);
	}

	/**
	 * @throws Exception
	 */
	public function createBook(string $userid, string $title, string $author, int $position, string $url, int $file, string $colour, int $pattern, int $height): Book {
		$book = new Book();
		$book->setUserid($userid);
		$book->setTitle($title);
		$book->setAuthor($author);
		$book->setPosition($position);
		$book->setUrl($url);
		$book->setFile($file);
		$book->setColour($colour);
		$book->setPattern($pattern);
		$book->setHeight($height);
		return $this->insert($book);
	}

	/**
	 * @param int $id
	 * @param string $userid
	 * @param string|null $title
	 * @param string|null $author
	 * @param int|null $position
	 * @param string|null $url
	 * @param int|null $file
	 * @param string|null $colour
	 * @param int|null $pattern
	 * @param int|null $height
	 * @return Book
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function updateBook(int $id, string $userid, ?string $title = null, ?string $author = null, ?int $position = null, ?string $url = null, ?int $file = null, ?string $colour = null, ?int $pattern = null, ?int $height = null): Book {
		$book = $this->getBookOfUser($id, $userid); // Will throw DoesNotExistException if not found

		if ($title !== null) {
			$book->setTitle($title);
		}

		if ($author !== null) {
			$book->setAuthor($author);
		}

		if ($position !== null) {
			$book->setPosition($position);
		}

		if ($url !== null) {
			$book->setUrl($url);
		}

		if ($file !== null) {
			$book->setFile($file);
		}

		if ($colour !== null) {
			$book->setColour($colour);
		}

		if ($pattern !== null) {
			$book->setPattern($pattern);
		}

		if ($height !== null) {
			$book->setHeight($height);
		}

		return $this->update($book);
	}

	/**
	 * @param int $id
	 * @param string $userid
	 * @return Book
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function deleteBook(int $id, string $userid): Book {
		$book = $this->getBookOfUser($id, $userid); // Will throw DoesNotExistException if not found

		return $this->delete($book);
	}

	/**
	 * @throws Exception
	 */
	public function deleteBooksOfUser(string $userid): void {
		$qb = $this->db->getQueryBuilder();

		$qb->delete($this->getTableName())
			->where(
				$qb->expr()->eq('userid', $qb->createNamedParameter($userid))
			);
		$qb->executeStatement();
		$qb->resetQueryParts();
	}
}
