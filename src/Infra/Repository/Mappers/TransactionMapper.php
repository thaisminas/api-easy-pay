<?php

namespace App\Infra\Repository\Mappers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infra\Repository\Mappers\TransactionMapper")
 * @ORM\Table(name="transactions")
 */
class TransactionMapper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerMapper", inversedBy="transactionsAsPayee")
     * @ORM\JoinColumn(name="payee_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    public $payee;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerMapper", inversedBy="transactionsAsPayeer")
     * @ORM\JoinColumn(name="payeer_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    public $payeer;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $operationType;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}