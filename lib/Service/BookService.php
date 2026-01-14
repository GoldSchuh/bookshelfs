<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Service;

use Exception;
use OC\User\NoUserException;
use OCA\Bookshelfs\AppInfo\Application;
use OCA\Bookshelfs\Db\Book;
use OCA\Bookshelfs\Db\BookMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\GenericFileException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\Lock\LockedException;

class BookService {

    public function __construct(
        string $appName,
        private IRootFolder $rootFolder,
        private BookMapper $bookMapper
    ) {
    }
//
//    /**
//     * @param string $userId
//     * @return Folder
//     * @throws NotPermittedException
//     * @throws NotFoundException
//     * @throws NoUserException
//     */
//    private function createOrGetBooksDirectory(string $userId): Folder {
//        $userFolder = $this->rootFolder->getUserFolder($userId);
//        if ($userFolder->nodeExists(Application::NOTE_FOLDER_NAME)) {
//            $node = $userFolder->get(Application::NOTE_FOLDER_NAME);
//            if ($node instanceof Folder) {
//                return $node;
//            }
//            throw new Exception('/' . Application::NOTE_FOLDER_NAME . ' exists and is not a directory');
//        } else {
//            return $userFolder->newFolder(Application::NOTE_FOLDER_NAME);
//        }
//    }
//
//    /**
//     * @param int $BookId
//     * @param string $userId
//     * @return string
//     * @throws DoesNotExistException
//     * @throws MultipleObjectsReturnedException
//     * @throws NoUserException
//     * @throws NotFoundException
//     * @throws NotPermittedException
//     * @throws \OCP\DB\Exception
//     * @throws GenericFileException
//     * @throws LockedException
//     */
//    public function exportBook(int $BookId, string $userId): string {
//        $bookFolder = $this->createOrGetBooksDirectory($userId);
//        $book = $this->bookMapper->getBookOfUser($BookId, $userId);
//        $fileName = $book->getName() . '.txt';
//        $fileContent = $book->getContent();
//        if ($bookFolder->nodeExists($fileName)) {
//            $node = $bookFolder->get($fileName);
//            if ($node instanceof File) {
//                $node->putContent($fileContent);
//                return Application::NOTE_FOLDER_NAME . '/' . $fileName;
//            }
//            throw new Exception('/' . Application::NOTE_FOLDER_NAME . '/' . $fileName .' exists and is not a file');
//        } else {
//            $bookFolder->newFile($fileName, $fileContent);
//            return Application::NOTE_FOLDER_NAME . '/' . $fileName;
//        }
//    }
}
