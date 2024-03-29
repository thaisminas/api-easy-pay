<?php

namespace App\Infra\Repository;

use App\Application\Factory\WalletFactory;
use App\Application\Interfaces\WalletInterface;
use App\Domain\Customer;
use App\Domain\Transaction;
use App\Infra\Repository\Mappers\WalletMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WalletRepository extends ServiceEntityRepository implements WalletInterface
{
    private $walletFactory;
    public function __construct(ManagerRegistry $registry, WalletFactory $walletFactory)
    {
        parent::__construct($registry, WalletMapper::class);
        $this->walletFactory = $walletFactory;
    }

    public function save(Customer $customer, ?array $operation): WalletMapper
    {
        $walletMapper = $this->walletFactory->toDatabase($customer->getId(), $operation);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($walletMapper);
        $entityManager->flush();

        return $walletMapper;
    }

    public function findAccountBalanceByCustomerId(int $customerId): WalletMapper
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('wallet')
            ->from(WalletMapper::class, 'wallet')
            ->where('wallet.customer = :id')
            ->setParameter('id', $customerId);

        $wallet = $queryBuilder->getQuery()->getSingleResult();

        return $wallet;
    }

    public function findCustomerByPayeeAndPayeer(Transaction $transaction): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('wallet')
            ->from(WalletMapper::class, 'wallet')
            ->where('wallet.customer = :payeeId OR wallet.customer = :payeerId')
            ->setParameter('payeeId', $transaction->getPayee()->getId())
            ->setParameter('payeerId', $transaction->getPayeer()->getId());

        $query = $queryBuilder->getQuery()->getResult();

        $walletCustomers = [];

        foreach ($query as $wallet){
            $customerId = $wallet->customer;
            $payee = $transaction->getPayee();
            $payeer = $transaction->getPayeer();

            if ($payee->getId() === $customerId || $payeer->getId() === $customerId) {
                $customerOperation = ($payee->getId() === $customerId) ? $payee : $payeer;
                $walletCustomers[$customerId] = $this->walletFactory->fromDatabase($wallet, $customerOperation);
            }
        }

        return $walletCustomers;
    }

    public function updateAccountBalanceByCustomerId(array $customer): void
    {
        $customerPayeeId = $customer['payee']['id'];
        $customerPayeerId = $customer['payeer']['id'];
        $newBalancePayee = $customer['payee']['amount'];
        $newBalancePayeer = $customer['payeer']['amount'];

        $connection = $this->getEntityManager()->getConnection();

        $sql = "
        UPDATE wallets 
        SET account_balance = 
            CASE 
                WHEN customer = :customerPayeeId THEN :newBalancePayee
                WHEN customer = :customerPayeerId THEN :newBalancePayeer
            END,
            updated_at = NOW()
        WHERE (customer = :customerPayeeId OR customer = :customerPayeerId) 
    ";

        $params = [
            'customerPayeeId' => $customerPayeeId,
            'customerPayeerId' => $customerPayeerId,
            'newBalancePayee' => $newBalancePayee,
            'newBalancePayeer' => $newBalancePayeer,
            'customerIds' => [$customerPayeeId, $customerPayeerId]
        ];

        $types = [
            'customerPayeeId' => \PDO::PARAM_INT,
            'customerPayeerId' => \PDO::PARAM_INT,
            'newBalancePayee' => \PDO::PARAM_STR,
            'newBalancePayeer' => \PDO::PARAM_STR
        ];

        $connection->executeQuery($sql, $params, $types);
    }

}
