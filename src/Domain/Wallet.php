<?php

namespace App\Domain;

use DateTime;

class Wallet
{
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

    /**
     * @var DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
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