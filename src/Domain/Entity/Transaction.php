<?php

namespace App\Domain\Entity;

use DateTime;

class Transaction
{
    /**
     * @var User
     */
    private $payee;

    /**
     * @var User
     */
    private $payeer;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $operationType;

    /**
     * @var DateTime
     */
    private $createdAt;

    public function getPayee(): User
    {
        return $this->payee;
    }

    public function setPayee(User $payee): void
    {
        $this->payee = $payee;
    }

    public function getPayeer(): User
    {
        return $this->payeer;
    }

    public function setPayeer(User $payeer): void
    {
        $this->payeer = $payeer;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType): void
    {
        $this->operationType = $operationType;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}