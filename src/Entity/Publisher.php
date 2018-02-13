<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="publishers")
 * @ORM\Entity
 */
class Publisher
{
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
     * @return array
     */
    public function getAttributes(): array
    {
		return [
			'id' => $this->id,
			'name' => $this->name,
		];
	}
}

