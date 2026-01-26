<?php

//
//  - SPDX-FileCopyrightText: 2026 Kars van Velzen
//  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
//  - SPDX-License-Identifier: AGPL-3.0-or-later
//
declare(strict_types=1);

namespace unit\Mapper;

use OC;
use OCA\Bookshelfs\Db\BookMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\DB\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @group DB
 */
class BooksMapperTest extends TestCase {
	private BookMapper $bookMapper;
	private array $testBookValues = [
		['userid' => 'user1', 'id' => 0, 'title' => 'Batman Philosophy' , 'author' => 'B', 'position' => 50 , 'url' => 'https://nextcloud.local', 'file' => 9, 'colour' => 'green' , 'pattern' => 1, 'height' => 250],
	];

	public function setUp(): void {
		parent::setUp();
		OC::$server->getAppManager()->enableApp('bookshelfs');
		$this->bookMapper = OC::$server->get(BookMapper::class);
	}

	/**
	 * @throws Exception
	 */
	public function tearDown(): void {
		$this->cleanupUser('user1');
	}

	/**
	 * @throws Exception
	 */
	private function cleanupUser(string $userid): void {
		$this->bookMapper->deleteBooksOfUser($userid);
	}

	/**
	 * @throws Exception
	 */
	public function testCreateBook() {
		foreach ($this->testBookValues as $book) {
			$addedBook = $this->bookMapper->createBook(userid: $book['userid'], title: $book['title'], author: $book['author'], position: $book['position'], url: $book['url'], file: $book['file'], colour: $book['colour'], pattern: $book['pattern'], height: $book['height']);
			self::assertEquals($book['userid'], $addedBook->getUserid());
			self::assertEquals($book['title'], $addedBook->getTitle());
			self::assertEquals($book['author'], $addedBook->getAuthor());
			self::assertEquals($book['position'], $addedBook->getPosition());
			self::assertEquals($book['url'], $addedBook->getUrl());
			self::assertEquals($book['file'], $addedBook->getFile());
			self::assertEquals($book['colour'], $addedBook->getColour());
			self::assertEquals($book['pattern'], $addedBook->getPattern());
			self::assertEquals($book['height'], $addedBook->getHeight());
		}
	}

	/**
	 * @throws MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 * @throws Exception
	 */
	public function testDeleteBook() {
		foreach ($this->testBookValues as $book) {
			$addedBook = $this->bookMapper->createBook(userid: 'user1', title: $book['title'], author: $book['author'], position: $book['position'], url: $book['url'], file: $book['file'], colour: $book['colour'], pattern: $book['pattern'], height: $book['height']);
			$addedBookId = $addedBook->getId();
			$this->bookMapper->deleteBook($addedBookId, $book['userid']);
			$exceptionThrown = false;
			try {
				$this->bookMapper->getBookOfUser($addedBookId, $book['userid']);
			} catch (DoesNotExistException) {
				$exceptionThrown = true;
			}
			$this->assertTrue($exceptionThrown, 'deleted book still exists');
		}
	}

	/**
	 * @throws MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 * @throws Exception
	 */
	public function testUpdateBook() {
		foreach ($this->testBookValues as $book) {
			$addedBook = $this->bookMapper->createBook(userid: 'user1', title: $book['title'], author: $book['author'], position: $book['position'], url: $book['url'], file: $book['file'], colour: $book['colour'], pattern: $book['pattern'], height: $book['height']);
			$addedBookId = $addedBook->getId();

			$editedBook = $this->bookMapper->updateBook($addedBookId, $book['userid'], $book['title'] . 'AAA', $book['author'] . 'BBB');
			self::assertEquals($book['title'] . 'AAA', $editedBook->getTitle());
			self::assertEquals($book['author'] . 'BBB', $editedBook->getAuthor());

			$dbBook = $this->bookMapper->getBookOfUser($addedBookId, $book['userid']);
			self::assertEquals($book['title'] . 'AAA', $dbBook->getTitle());
			self::assertEquals($book['author'] . 'BBB', $dbBook->getAuthor());
		}
	}
}
