<?php

namespace App\Infra\Repository;

use App\Application\Factory\WalletFactory;
use App\Domain\Customer;
use App\Domain\Port\Inbound\WalletRepositoryPort;
use App\Domain\Transaction;
use App\Domain\Wallet;
use App\Infra\Repository\Mappers\CustomerMapper;
use App\Infra\Repository\Mappers\WalletMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WalletRepositoryPortDatabase extends ServiceEntityRepository implements WalletRepositoryPort
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WalletMapper::class);
    }

    public function save(Customer $customer, ?array $operation): WalletMapper
    {
        $walletMapper = new WalletMapper();
        $walletMapper->customer = $customer->getId();
        $walletMapper->accountBalance = $operation['accountBalance'];
        $walletMapper->updatedAt = new \DateTime();

        $entityManager = $this->getEntityManager();
        $entityManager->persist($walletMapper);
        $entityManager->flush();

        return $walletMapper;
    }

    public function findAccountBalanceById(array $operation): Wallet
    {
        $customerOperation = $operation['customer'];

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('walllet')
            ->from(WalletMapper::class, 'wallet')
            ->where('wallet.id = :id')
            ->setParameter('id', $customerOperation)
            ->getQuery()
            ->getOneOrNullResult();

        $query = $qb->getQuery()->getSingleResult();

        return WalletFactory::createFromWalletMapper($query);
    }

    public function findCustomerByPayeeAndPayeer(Transaction $transaction): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('walllet')
            ->from(WalletMapper::class, 'walllet')
            ->where('walllet.customer = :payeeId OR walllet.customer = :payeerId')
            ->setParameter('payeeId', $transaction->getPayee()->getId())
            ->setParameter('payeerId', $transaction->getPayeer()->getId());

        $query = $qb->getQuery()->getResult();

        $walletCustomers = [];

        foreach ($query as $wallet){
            $customerId = $wallet->customer;
            $payee = $transaction->getPayee();
            $payeer = $transaction->getPayeer();

            if ($payee->getId() === $customerId || $payeer->getId() === $customerId) {
                $customerOperation = ($payee->getId() === $customerId) ? $payee : $payeer;
                $walletCustomers[$customerId] = [
                    'wallet' => WalletFactory::createFromWalletMapper($wallet, $customerOperation)
                ];
            }
        }

        return $walletCustomers;
    }
}
