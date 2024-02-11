<?php


namespace App\Seeds;

use App\Infra\Repository\Mappers\CustomerMapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class CustomerSeedCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:seed-customer')
            ->setDescription('Populate database with initial data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customers = [
            [
                'name' => 'Ferreira e Neto Lojista Ltda',
                'document' => '49283576000100',
                'email' => 'ferreira.lojista@gmail.com',
                'role' => 'STORE_USER',
                'password' => '12345'
            ],
            [
                'name' => 'Deise Miranda',
                'document' => '11356789043',
                'email' => 'deise.miranda@gmail.com',
                'role' => 'COMMON',
                'password' => '345667'
            ],
            [
                'name' => 'Thais Silva',
                'document' => '70907928030',
                'email' => 'thais.silva@gmail.com',
                'role' => 'COMMON',
                'password' => '09955'
            ],
            [
                'name' => 'Comercial Miranda Ltda',
                'document' => '99930053000179',
                'email' => 'comercial.miranda@gmail.com',
                'role' => 'STORE_USER',
                'password' => '54545'
            ],
            [
                'name' => 'Carla Figueiredo da Silva',
                'document' => '79640779024',
                'email' => 'carla.silva@gmail.com',
                'role' => 'COMMON',
                'password' => 'dsf099'
            ],
        ];

        foreach ($customers as $customer){
            $newCustomer = new CustomerMapper();
            $newCustomer->name = $customer['name'];
            $newCustomer->document = $customer['document'];
            $newCustomer->email = $customer['email'];
            $newCustomer->role = $customer['role'];
            $newCustomer->password = $customer['password'];

            $this->entityManager->persist($newCustomer);
            $this->entityManager->flush();
        }

        $output->writeln('Successfully created seed data');

        return Command::SUCCESS;
    }
}
