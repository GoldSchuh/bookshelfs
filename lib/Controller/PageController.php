<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Controller;

use Exception;
use OCA\Bookshelfs\AppInfo\Application;
use OCA\Bookshelfs\Db\BookMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Collaboration\Reference\RenderReferenceEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IRequest;
use Throwable;

class PageController extends Controller {

	public function __construct(
		string $appName,
		IRequest $request,
		private readonly IEventDispatcher $eventDispatcher,
		private readonly IInitialState $initialStateService,
		private readonly BookMapper $bookMapper,
		private readonly ?string $userId,
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @return TemplateResponse
	 */
	#[NoAdminRequired]
	#[NoCSRFRequired]
	#[FrontpageRoute(verb: 'GET', url: '/')]
	public function index(): TemplateResponse {
		$this->eventDispatcher->dispatchTyped(new RenderReferenceEvent());
		try {
			$books = $this->bookMapper->getBooksOfUser($this->userId);
		} catch (Exception|Throwable) {
			$books = [];
		}

		$state = [
			'$books' => $books,
		];
		$this->initialStateService->provideInitialState('bookshelfs-initial-state', $state);
		return new TemplateResponse(Application::APP_ID, 'index');
	}
}
