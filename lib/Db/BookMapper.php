<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use DateTime;
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
	 * @return Book
	 * @throws Exception
	 */
	public function createBook(string $userId, string $title, string $author): Book {
		$book = new Book();
		$book->setUserId($userId);
		$book->setTitle($title);
		$book->setAuthor($author);
		return $this->insert($book);
	}

	/**
	 * @param int $id
	 * @param string $userId
	 * @param string $title
	 * @param string $author
	 * @return Book |null
	 * @throws Exception
	 */
	public function updateBook(int $id, string $userId, ?string $title = null, ?string $author = null): ?Book {
		if ($title === null && $author === null) {
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
