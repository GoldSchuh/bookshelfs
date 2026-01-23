<?php

declare(strict_types=1);

namespace OCA\Bookshelfs\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;
use ReturnTypeWillChange;

/**
 * @method string getUserId()
 * @method void setUserId(?string $userId)
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method string getAuthor()
 * @method void setAuthor(string $author)
 * @method string getPosition()
 * @method void setPosition(int $position)
 * @method string getUrl()
 * @method void setUrl(string $url)
 * @method string getFile()
 * @method void setFile(int $file)
 * @method string getColour()
 * @method void setColour(string $colour)
 * @method string getPattern()
 * @method void setPattern(int $pattern)
 * @method string getHeight()
 * @method void setHeight(int $height)
 */
class Book extends Entity implements JsonSerializable {
	protected string $userId = '';
	protected string $title = '';
	protected string $author = '';
	protected int $position = 0;
	protected string $url = '';
	protected int $file = 0;
	protected string $colour = '';
	protected int $pattern = 0;
	protected int $height = 0;

	public function __construct() {
		$this->addType('userId', 'string');
		$this->addType('title', 'string');
		$this->addType('author', 'string');
		$this->addType('position', 'int');
		$this->addType('url', 'text');
		$this->addType('file', 'int');
		$this->addType('colour', 'text');
		$this->addType('pattern', 'int');
		$this->addType('height', 'int');
	}

	#[ReturnTypeWillChange]
	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'title' => $this->title,
			'author' => $this->author,
			'position' => $this->position,
			'url' => $this->url,
			'file' => $this->file,
			'colour' => $this->colour,
			'pattern' => $this->pattern,
			'height' => $this->height,
		];
	}
}
