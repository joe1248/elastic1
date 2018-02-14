<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="books")
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cover_url;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $isbn;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;
	
    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured;

    /**
     * @var Publisher
     *
     * Several Books belongs to One Publisher.
     * @ORM\ManyToOne(targetEntity="Publisher")
     * @ORM\JoinColumn(name="publisher_id", referencedColumnName="id")
     */
    private $publisher;

    /**
     * @var Author
     *
     * Several Books written by one author
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * Book constructor.
     *
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['id'] ?? null;
        $this->title = $input['title'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->cover_url =  $input['cover_url'] ?? null;
        $this->isbn =  $input['isbn'] ?? null;
        $this->featured = $input['featured'] ?? false;
        $this->deleted = $input['deleted'] ?? false;
        $this->publisher = $input['publisher'] ?? null;
        $this->author = $input['author'] ?? null;
        //$this->validate();
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'cover_url' => $this->cover_url,
            'isbn' => $this->isbn,
            'publisher' => $this->publisher->getAttributes(),
            'author' => $this->author->getAttributes(),
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}