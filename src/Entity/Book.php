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
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;
	
    /**
     * @ORM\Column(name="featured", type="boolean")
     */
    private $featured;
}

