<?php

namespace App\Domain;

use DateTime;

class Transaction
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Customer
     */
    private $payee;

    /**
     * @var Customer
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

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPayee(): Customer
    {
        return $this->payee;
    }

    public function setPayee(Customer $payee): void
    {
        $this->payee = $payee;
    }

    public function getPayeer(): Customer
    {
        return $this->payeer;
    }

    public function setPayeer(Customer $payeer): void
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}