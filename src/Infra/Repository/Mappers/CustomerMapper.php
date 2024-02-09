<?php

namespace App\Infra\Repository\Mappers;

use App\Domain\Customer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infra\Repository\Mappers\CustomerMapper")
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

    public function fromDatabase(CustomerMapper $customerMapper): Customer
    {
        $customer = new Customer();
        $customer->setId($customerMapper->id);
        $customer->setName($customerMapper->name);
        $customer->setEmail($customerMapper->email);
        $customer->setSsn($customerMapper->ssn);
        $customer->setRole($customerMapper->role);
        $customer->setPassword($customerMapper->password);

        return $customer;
    }
}