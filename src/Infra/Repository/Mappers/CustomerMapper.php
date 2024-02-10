<?php

namespace App\Infra\Repository\Mappers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infra\Repository\Mappers\CustomerMapper")
 * @ORM\Table(name="customers")
 */
class CustomerMapper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    public $document;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=30)
     */
    public $password;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $role;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}