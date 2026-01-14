<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Controller;

use Exception;
use OCA\Bookshelfs\Db\BookMapper;
use OCA\Bookshelfs\Service\BookService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class BooksController extends OCSController {

	public const REQUIREMENTS = [
		'apiVersion' => 'v1',
	];

	public function __construct(
		string $appName,
		IRequest $request,
		private BookMapper $bookMapper,
		private BookService $bookService,
		private ?string $userId,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @return DataResponse
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'GET', url: '/api/{apiVersion}/books', requirements: self::REQUIREMENTS)]
	public function getUserBooks(): DataResponse {
		try {
			return new DataResponse($this->bookMapper->getBooksOfUser($this->userId));
		} catch (Exception|Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param string $title
	 * @param string $author
	 * @return DataResponse
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'POST', url: '/api/{apiVersion}/books', requirements: self::REQUIREMENTS)]
	public function addUserBook(string $title, string $author = '', int $position=-1, string $url= '', int $file=-1): DataResponse {
		try {
			$book = $this->bookMapper->createBook($this->userId, $title, $author, $position, $url, $file);
			return new DataResponse($book);
		} catch (Exception|Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param int $id
	 * @param string|null $title
	 * @param string|null $author
	 * @return DataResponse
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'PUT', url: '/api/{apiVersion}/books/{id}', requirements: self::REQUIREMENTS)]
	public function editUserBook(int $id, ?string $title = null, ?string $author = null, ?int $position = null, ?string $url = null, ?int $file = null): DataResponse {
		try {
			$book = $this->bookMapper->updateBook($id, $this->userId, $title, $author, $position, $url, $file);
			return new DataResponse($book);
		} catch (Exception|Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param int $id
	 * @return DataResponse
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'DELETE', url: '/api/{apiVersion}/books/{id}', requirements: self::REQUIREMENTS)]
	public function deleteUserBook(int $id): DataResponse {
		try {
			$book = $this->bookMapper->deleteBook($id, $this->userId);
			return new DataResponse($book);
		} catch (Exception|Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param int $id
	 * @return DataResponse
	 */
	#[ApiRoute(verb: 'GET', url: '/api/{apiVersion}/books/{id}/export', requirements: self::REQUIREMENTS)]
	public function exportUserBook(int $id): DataResponse {
		try {
			$path = $this->bookService->exportBook($id, $this->userId);
			return new DataResponse($path);
		} catch (Exception|Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}
}
