<?php

namespace App\Domain\Entity;

use DateTime;

class Wallet
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var float
     */
    private $accountBalance;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
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

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}