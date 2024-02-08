<?php

namespace App\Infra\Repository\Mappers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Doctrine\TransactionRepository")
 * @ORM\Table(name="transactions")
 */
class TransactionMapper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerEntityMapper", inversedBy="transactionsAsPayee")
     * @ORM\JoinColumn(name="payee_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $payee;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerEntityMapper", inversedBy="transactionsAsPayeer")
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

    public function toDatabaseEntity(Transaction $transaction): TransactionMapper
    {
        $this->payee = $transaction->getPayee();
        $this->payeer = $transaction->getPayeer();
        $this->amount = $transaction->getAmount();
        $this->operationType = $transaction->getOperationType();

        return $this;
    }

//    public function fromDatabaseEntity(Transaction $transactionEntityMapper): Transaction
//    {
//        $transaction = new Transaction();
//        $transaction->setPayee($transactionEntityMapper->payee);
//        $transaction->setPayeer($transactionEntityMapper->payeer);
//        $transaction->setAmount($transactionEntityMapper->amount);
//        $transaction->setOperationType($transactionEntityMapper->operationType);
//        $transaction->setId($transactionEntityMapper->id);
//
//        return $transaction;
//    }
}