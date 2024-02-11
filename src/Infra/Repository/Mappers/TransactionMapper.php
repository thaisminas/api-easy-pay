<?php

namespace App\Infra\Repository\Mappers;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

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
     * @OA\Property(
     *       example="1"
     * )
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerMapper", inversedBy="transactionsAsPayee")
     * @ORM\JoinColumn(name="payee_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     * @OA\Property(
     *        example="3"
     * )
     */
    public $payee;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerMapper", inversedBy="transactionsAsPayeer")
     * @ORM\JoinColumn(name="payeer_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     * @OA\Property(
     *         example="1"
     *  )
     */
    public $payeer;

    /**
     * @ORM\Column(type="float")
     * @OA\Property(
     *         example="250.00"
     *  )
     */
    public $amount;

    /**
     * @ORM\Column(type="string", length=10)
     * @OA\Property(
     *         example="credit"
     *  )
     */
    public $operationType;

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property(
     *       format="date-time",
     *       example="2024-02-17T10:20:45Z"
     *   )
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}