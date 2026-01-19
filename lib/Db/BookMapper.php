<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;

use OCP\IDBConnection;

class BookMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'bookshelfs', Book::class);
	}

	/**
	 * @param int $id
	 * @return Book
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function getBook(int $id): Book {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @param int $id
	 * @param string $userId
	 * @return Book
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function getBookOfUser(int $id, string $userId): Book {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			)
			->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @param string $userId
	 * @return Book[]
	 * @throws Exception
	 */
	public function getBooksOfUser(string $userId): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntities($qb);
	}

	/**
	 * @param string $userId
	 * @param string $title
	 * @param string $author
	 * @param int $position
	 * @param string $url
	 * @param int $file
	 * @param string $colour
	 * @param int $pattern
	 * @param int $height
	 * @return Book
	 * @throws Exception
	 */
	public function createBook(string $userId, string $title, string $author, int $position, string $url, int $file, string $colour, int $pattern, int $height): Book {
		$book = new Book();
		$book->setUserId($userId);
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
	 * @param string $userId
	 * @param string $title
	 * @param string $author
	 * @param int $position
	 * @param string $url
	 * @param int $file
	 * @param string $colour
	 * @param int $pattern
	 * @param int $height
	 * @return Book |null
	 * @throws Exception
	 */
	public function updateBook(int $id, string $userId, ?string $title = null, ?string $author = null, ?int $position = null, ?string $url = null, ?int $file, ?string $colour = null, ?int $pattern = null, ?int $height = null): ?Book {
		if ($title === null && $author === null && $position === null && $url === null && $file === null && $colour === null && $pattern === null && $height === null) {
			return null;
		}
		try {
			$book = $this->getBookOfUser($id, $userId);
		} catch (DoesNotExistException|MultipleObjectsReturnedException $e) {
			return null;
		}
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
	 * @param string $userId
	 * @return Book|null
	 * @throws Exception
	 */
	public function deleteBook(int $id, string $userId): ?Book {
		try {
			$book = $this->getBookOfUser($id, $userId);
		} catch (DoesNotExistException|MultipleObjectsReturnedException $e) {
			return null;
		}

		return $this->delete($book);
	}

	/**
	 * @param string $userId
	 * @return void
	 * @throws Exception
	 */
	public function deleteBooksOfUser(string $userId): void {
		$qb = $this->db->getQueryBuilder();

		$qb->delete($this->getTableName())
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);
		$qb->executeStatement();
		$qb->resetQueryParts();
	}
}
