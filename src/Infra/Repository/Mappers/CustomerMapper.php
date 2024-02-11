<?php

namespace App\Infra\Repository\Mappers;


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

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
     * @OA\Property(
     *      example="1"
     *  )
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @OA\Property(
     *      example="John Doe"
     *  )
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @OA\Property(
     *      example="13976071064"
     *  )
     */
    public $document;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @OA\Property(
     *      example="john@example.com"
     *  )
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=30)
     * @OA\Property(
     *       example="1234"
     *   )
     */
    public $password;

    /**
     * @ORM\Column(type="string", length=10)
     * @OA\Property(
     *       example="store_user"
     *   )
     */
    public $role;

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property(
     *      format="date-time",
     *      example="2024-02-17T10:20:45Z"
     *  )
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}