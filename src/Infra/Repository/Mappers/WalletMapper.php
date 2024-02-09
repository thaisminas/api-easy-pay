<?php

namespace App\Infra\Repository\Mappers;

use App\Domain\Customer;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

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
     */
    public $id;

    /**
     * @ORM\OneToOne(targetEntity="CustomerMapper", inversedBy="wallet")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @ORM\Column(type="integer")
     */
    public $customer;

    /**
     * @ORM\Column(type="float")
     */
    public $accountBalance;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    public $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function toDatabase(Customer $customer, ?array $operation): WalletMapper
    {
        $this->customer = $customer->getId();
        $this->accountBalance = $operation['accountBalance'];
        $this->updatedAt = new DateTime();

        return $this;
    }
}