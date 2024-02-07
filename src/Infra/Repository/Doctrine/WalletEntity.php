<?php

namespace App\Infra\Repository\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletRepository")
 * @ORM\Table(name="wallets")
 */
class WalletEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="CustomerEntity", inversedBy="wallet")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @ORM\Column(type="integer")
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     */
    private $accountBalance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): CustomerEntity
    {
        return $this->user;
    }

    public function setUser(CustomerEntity $user): void
    {
        $this->user = $user;
    }

    public function getAccountBalance(): float
    {
        return $this->accountBalance;
    }

    public function setAccountBalance(float $accountBalance): void
    {
        $this->accountBalance = $accountBalance;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}