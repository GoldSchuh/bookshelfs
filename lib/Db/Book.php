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
 * @method string getPosition()
 * @method void setPosition(int $position)
 */
class Book extends Entity implements \JsonSerializable {

	/** @var string */
	protected $userId;
	/** @var string */
	protected $title;
	/** @var string */
	protected $author;
	/** @var int */
    protected $position;

	public function __construct() {
		$this->addType('userId', 'string');
		$this->addType('title', 'string');
		$this->addType('author', 'string');
		$this->addType('position', 'int');
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'title' => $this->title,
			'author' => $this->author,
			'position' => $this->position,
		];
	}
}
