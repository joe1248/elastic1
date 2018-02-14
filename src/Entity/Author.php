<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="authors")
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * Publisher constructor.
     *
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['id'] ?? null;
        $this->first_name = $input['first_name'] ?? null;
        $this->last_name = $input['last_name'] ?? null;
        $this->deleted = $input['deleted'] ?? false;
        //$this->validate();
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
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