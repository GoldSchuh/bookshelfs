<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string|null getUserId()
 * @method void setUserId(?string $userId)
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method string getAuthor()
 * @method void setAuthor(string $author)
 */
class Book extends Entity implements \JsonSerializable {

    /** @var string */
    protected $userId;
    /** @var string */
    protected $title;
    /** @var string */
    protected $author;

    public function __construct() {
        $this->addType('userId', 'string');
        $this->addType('title', 'string');
        $this->addType('author', 'string');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'author' => $this->author,
        ];
    }
}
