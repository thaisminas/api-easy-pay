<?php

namespace App\Infra\Repository\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Entity\Transaction;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Doctrine\TransactionRepository")
 * @ORM\Table(name="transactions")
 */
class TransactionEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerEntity", inversedBy="transactionsAsPayee")
     * @ORM\JoinColumn(name="payee_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $payee;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerEntity", inversedBy="transactionsAsPayeer")
     * @ORM\JoinColumn(name="payeer_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $payeer;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string")
     */
    private $operationType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayee(): ?CustomerEntity
    {
        return $this->payee;
    }

    public function setPayee(CustomerEntity $payee): void
    {
        $this->payee = $payee;
    }

    public function getPayeer(): ?CustomerEntity
    {
        return $this->payeer;
    }

    public function setPayeer(CustomerEntity $payeer): void
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

    public function toDomainEntity(): Transaction
    {
        $transaction = new Transaction();
        $transaction->setPayee($this->getPayee());
        $transaction->setPayeer($this->getPayeer());
        $transaction->setAmount($this->getAmount());
        $transaction->setOperationType($this->getOperationType());
        $transaction->setCreatedAt($this->getCreatedAt());

        return $transaction;
    }
}