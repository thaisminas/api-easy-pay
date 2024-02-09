<?php

namespace App\Infra\Repository;

use App\Domain\Customer;
use App\Domain\Port\Inbound\WalletRepositoryPort;
use App\Domain\Wallet;
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

        $entityManager = $this->getEntityManager();
        $entityManager->persist($walletMapper);
        $entityManager->flush();

        return $walletMapper;
    }
}
