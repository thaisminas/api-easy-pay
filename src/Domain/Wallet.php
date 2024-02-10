<?php

namespace App\Domain;

use DateTime;

class Wallet
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var float
     */
    private $accountBalance;

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

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getAccountBalance(): float
    {
        return $this->accountBalance;
    }

    public function setAccountBalance(float $accountBalance): void
    {
        $this->accountBalance = $accountBalance;
    }
}