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
     * @ORM\Column(type="string", length=100)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    public $document;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    public $role;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function toDatabase(Customer $customer): CustomerMapper
    {
        $this->name = $customer->getName();
        $this->document = $customer->getDocument();
        $this->email = $customer->getEmail();
        $this->role = $customer->getRole();

        return $this;
    }

    public function fromDatabase(CustomerMapper $customerMapper): Customer
    {
        $customer = new Customer();
        $customer->setId($customerMapper->id);
        $customer->setName($customerMapper->name);
        $customer->setEmail($customerMapper->email);
        $customer->setDocument($customerMapper->document);
        $customer->setRole($customerMapper->role);

        return $customer;
    }
}