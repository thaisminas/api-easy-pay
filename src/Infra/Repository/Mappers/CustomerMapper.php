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
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $ssn;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSsn()
    {
        return $this->ssn;
    }

    /**
     * @param mixed $ssn
     */
    public function setSsn($ssn): void
    {
        $this->ssn = $ssn;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

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