<?php

namespace App\Entity;

// this use statement is needed for the annotations
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="publishers")
 * @ORM\Entity(repositoryClass="App\Repository\PublisherRepository")
 */
class Publisher
{
    const REQUIRED_FIELDS = [
        'name',
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
	
    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $name;

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
        $this->name = $input['name'] ?? null;
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
            'name' => $this->name,
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