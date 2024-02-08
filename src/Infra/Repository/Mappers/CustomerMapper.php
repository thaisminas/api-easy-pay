<?php

namespace App\Infra\Repository\Mappers;

use App\Domain\Customer\Customer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Customer\Port\Inbound\CustomerRepository")
 * @ORM\Table(name="customers")
 */
class CustomerMapper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    public $ssn;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    public $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    public $role;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    public $updatedAt;

    public function toDatabase(Customer $customer): CustomerMapper
    {
        $this->name = $customer->getName();
        $this->ssn = $customer->getSsn();
        $this->email = $customer->getEmail();
        $this->role = $customer->getRole();
        $this->password = $customer->getPassword();

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