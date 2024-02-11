<?php

namespace App\Infra\Repository\Mappers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass="App\Infra\Repository\Mappers\WalletMapper")
 * @ORM\Table(name="wallets")
 */
class WalletMapper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @OA\Property(
     *        example="1"
     *  )
     */
    public $id;

    /**
     * @ORM\OneToOne(targetEntity="CustomerMapper", inversedBy="wallet")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     * @ORM\Column(type="integer", unique=true)
     * @OA\Property(
     *         example="1"
     * )
     */
    public $customer;

    /**
     * @ORM\Column(type="float")
     * @OA\Property(
     *         example="550.80"
     * )
     */
    public $accountBalance;

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property(
     *       format="date-time",
     *       example="2024-02-17T10:20:45Z"
     *   )
     */
    public $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property(
     *       format="date-time",
     *       example="2024-02-17T12:20:45Z"
     *   )
     */
    public $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}