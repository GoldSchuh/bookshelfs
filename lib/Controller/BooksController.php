<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Controller;

use OCA\Bookshelfs\Db\BookMapper;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class BooksController extends OCSController {
	public function __construct(
		string $appName,
		IRequest $request,
		private readonly BookMapper $bookMapper,
		private readonly ?string $userId,
	) {
		parent::__construct($appName, $request);
	}


	/**
	 * Return a list of all books of the current user
	 *
	 * @return DataResponse<Http::STATUS_OK, list<array{id: int, title: string , author: string, position: int , url: string, file: int, colour: string , pattern: int, height: int}> , array{}> | DataResponse<Http::STATUS_BAD_REQUEST, list<string>, array{}>
	 *
	 * @response 200: All user books returned successfully
	 * @response 400: Bad request
	 */
	#[NoAdminRequired]
	#[ApiRoute(
		verb: 'GET',
		url: '/api/v1/books',
	)]
	public function getUserBooks(): DataResponse {
		try {
			return new DataResponse($this->bookMapper->getBooksOfUser($this->userId));
		} catch (Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Create a new book for the current user
	 *
	 * @param string $title Title of the book
	 * @param string $author Author of the book
	 * @param int $position Position of the book in the shelf (array index)
	 * @param string $url File ID of the book cover image
	 * @param int $file File ID of the (e-)book file
	 * @param string $colour Color of the book
	 * @param int $pattern Pattern of the book
	 * @param int $height Height of the book
	 *
	 * @return DataResponse<Http::STATUS_OK, array{id: int, title: string , author: string, position: int , url: string, file: int, colour: string , pattern: int, height: int} , array{}> | DataResponse<Http::STATUS_BAD_REQUEST, list<string>, array{}>
	 *
	 * @response 200: Created and returned the book successfully
	 * @response 400: Bad request
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'POST', url: '/api/v1/books')]
	public function addUserBook(string $title, string $author, int $position, string $url, int $file, string $colour, int $pattern, int $height): DataResponse {
		try {
			$book = $this->bookMapper->createBook($this->userId, $title, $author, $position, $url, $file, $colour, $pattern, $height);
			return new DataResponse($book);
		} catch (Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Update the parameters of an existing book (id) of the current user
	 *
	 * @param int $id Identifier of the book to edit
	 * @param string|null $title New title of the book
	 * @param string|null $author New author of the book
	 * @param int|null $position New position of the book in the shelf (array index)
	 * @param string|null $url New image (file ID) for the book cover image
	 * @param int|null $file New e-book (file ID) for the book
	 * @param string|null $colour New color of the book
	 * @param int|null $pattern New pattern of the book
	 * @param int|null $height New height of the book
	 *
	 * @return DataResponse<Http::STATUS_OK, array{id: int, title: string , author: string, position: int , url: string, file: int, colour: string , pattern: int, height: int} , array{}> | DataResponse<Http::STATUS_BAD_REQUEST, list<string>, array{}>
	 *
	 * @response 200: Updated and returned the updated book successfully
	 * @response 400: Bad request
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'PUT', url: '/api/v1/books/{id}')]
	public function editUserBook(int $id, ?string $title = null, ?string $author = null, ?int $position = null, ?string $url = null, ?int $file = null, ?string $colour = null, ?int $pattern = null, ?int $height = null): DataResponse {
		try {
			$book = $this->bookMapper->updateBook($id, $this->userId, $title, $author, $position, $url, $file, $colour, $pattern, $height);
			return new DataResponse($book);
		} catch (Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * Delete an existing book (id) of the current user
	 *
	 * @param int $id Identifier of the book to delete
	 *
	 * @return DataResponse<Http::STATUS_OK, array{id: int, title: string , author: string, position: int , url: string, file: int, colour: string , pattern: int, height: int} , array{}> | DataResponse<Http::STATUS_BAD_REQUEST, list<string>, array{}>
	 *
	 * @response 200: Deleted and returned the deleted book successfully
	 * @response 400: Bad request
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'DELETE', url: '/api/v1/books/{id}')]
	public function deleteUserBook(int $id): DataResponse {
		try {
			$book = $this->bookMapper->deleteBook($id, $this->userId);
			return new DataResponse($book);
		} catch (Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}
}
