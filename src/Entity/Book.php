<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="books")
 * @ORM\Entity(repositoryClass="App\Repository\bookRepository")
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
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $cover_url;

    /**
     * @ORM\Column(type="string", length=20)
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
        $this->title = $input['id'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->cover_url =  $input['cover_url'] ?? null;
        $this->isbn =  $input['isbn'] ?? null;
        $this->featured =  (bool) $input['featured'] ?? false;
        $this->deleted =  (bool) $input['deleted'] ?? false;
        $this->publisher = $input['publisher_id'] ?? null;
        $this->author = $input['author_id'] ?? null;
        //$this->validate();
    }
}