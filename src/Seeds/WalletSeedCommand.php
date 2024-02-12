<?php

namespace App\Seeds;

use App\Infra\Repository\Mappers\WalletMapper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WalletSeedCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:seed-wallet')
            ->setDescription('Populate database with initial data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customers = [
            [
                'customer' => 1,
                'accountBalance' => 350.00
            ],
            [
                'customer' => 2,
                'accountBalance' => 129.00
            ],
            [
                'customer' => 3,
                'accountBalance' => 1500.00
            ],
            [
                'customer' => 4,
                'accountBalance' => 3000.00
            ],
            [
                'customer' => 5,
                'accountBalance' => 500.00
            ]
        ];

        foreach ($customers as $customer){
            $newWallet = new WalletMapper();
            $newWallet->customer = $customer['customer'];
            $newWallet->accountBalance = $customer['accountBalance'];
            $newWallet->updatedAt = new DateTime();

            $this->entityManager->persist($newWallet);
            $this->entityManager->flush();
        }

        $output->writeln('Successfully created seed data');

        return Command::SUCCESS;
    }
}