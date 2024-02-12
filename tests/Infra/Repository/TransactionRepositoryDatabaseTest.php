<?php

namespace App\Tests\Infra\Repository;

use App\Application\Factory\TransactionFactory;
use App\Application\Helper\BalanceDataFormatter;
use App\Application\Interfaces\CustomerInterface;
use App\Application\Interfaces\ServiceAuthorizationInterface;
use App\Application\Interfaces\TransactionInterface;
use App\Application\Interfaces\WalletInterface;
use App\Application\UseCases\CreateTransaction;
use App\Application\UseCases\SendNotification;
use App\Domain\Customer;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\NotificationException;
use App\Domain\Exception\OperationTypeException;
use App\Domain\Exception\UnauthorizedOperationException;
use App\Domain\Transaction;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;
use Exception;
class TransactionRepositoryDatabaseTest extends TestCase
{
    protected $transactionInterfaceMock;
    protected $customerInterfaceMock;
    protected $serviceAuthorizationMock;
    protected $walletRepositoryMock;
    protected $transactionFactoryMock;
    protected $notificationUseCaseMock;
    protected $balanceFormatterDataMock;

    protected function setUp(): void
    {
        $this->customerInterfaceMock = $this->createMock(CustomerInterface::class);
        $this->transactionFactoryMock = $this->createMock(TransactionFactory::class);
        $this->walletRepositoryMock = $this->createMock(WalletInterface::class);
        $this->serviceAuthorizationMock = $this->createMock(ServiceAuthorizationInterface::class);
        $this->transactionInterfaceMock = $this->createMock(TransactionInterface::class);
        $this->notificationUseCaseMock = $this->createMock(SendNotification::class);
    }

    public function testTheCreateOfTransaction(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20.00);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->once())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->once())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->once())
            ->method('execute');

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->once())
            ->method('format')
            ->willReturn($formatData);

        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction = $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 3,
            'amount' => 15,
            'operationType' => 'credit'
        ]);

        $this->assertNull($createTransaction);
        $this->assertTrue(true);
    }

    public function testThrowsExceptionWhenOperationTypeIsInvalid(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20.00);
        $transaction->setOperationType('xx');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->never())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->never())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->never())
            ->method('execute');

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->never())
            ->method('format')
            ->willReturn($formatData);

        $this->expectException(OperationTypeException::class);

        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => 15.90,
            'operationType' => 'xx'
        ]);
    }

    public function testThrowsExceptionWhenTypeCustomerIsStoreUser(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('store_user'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20.00);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->never())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->never())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->never())
            ->method('execute');

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->never())
            ->method('format')
            ->willReturn($formatData);

        $this->expectException(UnauthorizedOperationException::class);

        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => 15.90,
            'operationType' => 'credit'
        ]);
    }

    public function testThrowsExceptionWhenAmountInWalletIsInsufficient(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20.00);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(0);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->never())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->never())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->never())
            ->method('execute');

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->never())
            ->method('format')
            ->willReturn($formatData);

        $this->expectException(InsufficientBalanceException::class);

        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => 15.90,
            'operationType' => 'credit'
        ]);
    }

    public function testThrowsExceptionWhenAmountTransactionIsNegativeOrZero(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(-1);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->never())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->never())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->never())
            ->method('execute');

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->never())
            ->method('format')
            ->willReturn($formatData);

        $this->expectException(OperationTypeException::class);

        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => -1,
            'operationType' => 'credit'
        ]);
    }

    public function testThrowsExceptionWhenOccurredFailureInTrySendNotification(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->once())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->once())
            ->method('create');

        $this->notificationUseCaseMock->expects($this->once())
            ->method('execute')
            ->willThrowException(new NotificationException('Failed to send notification after 3 retries.'));

        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->exactly(2))
            ->method('format')
            ->willReturn($formatData);

        $this->walletRepositoryMock->expects($this->exactly(2))
            ->method('updateAccountBalanceByCustomerId');


        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Failed to send notification after 3 retries.");
        $this->expectExceptionCode(400);


        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => 15.90,
            'operationType' => 'credit'
        ]);
    }

    public function testThrowsExceptionWhenOccurredFailureInTrySaveTransaction(): void
    {
        $customerPayee = new Customer();
        $customerPayee->setId(1);
        $customerPayee->setName('Thais Silva');
        $customerPayee->setEmail('thais.silva@gmail.com');
        $customerPayee->setDocument('794.352.290-77');
        $customerPayee->setRole(strtoupper('common'));
        $customerPayee->setPassword('1233');

        $customerPayeer = new Customer();
        $customerPayeer->setId(3);
        $customerPayeer->setName('Thais Silva');
        $customerPayeer->setEmail('thais.silva@gmail.com');
        $customerPayeer->setDocument('794.352.290-77');
        $customerPayeer->setRole(strtoupper('common'));
        $customerPayeer->setPassword('1233');

        $customers = [
            1 => $customerPayee,
            3 => $customerPayeer
        ];

        $this->customerInterfaceMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($customers);

        $transaction = new Transaction();
        $transaction->setPayee($customers[1]);
        $transaction->setPayeer($customers[3]);
        $transaction->setAmount(20);
        $transaction->setOperationType('CREDIT');

        $this->transactionFactoryMock->expects($this->once())
            ->method('createFromArray')
            ->willReturn($transaction);


        $walletPayee = new Wallet();
        $walletPayee->setCustomer($customers[1]);
        $walletPayee->setAccountBalance(200);

        $walletPayeer = new Wallet();
        $walletPayeer->setCustomer($customers[3]);
        $walletPayeer->setAccountBalance(400);

        $walletCustomers = [
            1 => $walletPayee,
            3 => $walletPayeer
        ];

        $this->walletRepositoryMock->expects($this->once())
            ->method('findCustomerByPayeeAndPayeer')
            ->willReturn($walletCustomers);


        $this->serviceAuthorizationMock->expects($this->once())
            ->method('getAuthorization');

        $this->transactionInterfaceMock->expects($this->once())
            ->method('create')
            ->willThrowException(new Exception('GenericError', 500));


        $this->notificationUseCaseMock->expects($this->never())
            ->method('execute');
        $formatData = [
            'payee' => [
                'id' => 1,
                'amount' => $walletCustomers[$transaction->getPayeer()->getId()]->getAccountBalance()
            ],
            'payeer' => [
                'id' => 3,
                'amount' => $walletCustomers[$transaction->getPayee()->getId()]->getAccountBalance(),
            ]
        ];

        $this->balanceFormatterDataMock = $this->createMock(BalanceDataFormatter::class);
        $this->balanceFormatterDataMock->expects($this->exactly(2))
            ->method('format')
            ->willReturn($formatData);

        $this->walletRepositoryMock->expects($this->exactly(2))
            ->method('updateAccountBalanceByCustomerId');


        $this->expectException(Exception::class);
        $this->expectExceptionMessage("GenericError");
        $this->expectExceptionCode(500);


        $createTransaction = new CreateTransaction(
            $this->transactionInterfaceMock,
            $this->customerInterfaceMock,
            $this->serviceAuthorizationMock,
            $this->walletRepositoryMock,
            $this->transactionFactoryMock,
            $this->notificationUseCaseMock,
            $this->balanceFormatterDataMock
        );

        $createTransaction->execute([
            'payeeId' => 1,
            'payeerId' => 4,
            'amount' => 15.90,
            'operationType' => 'credit'
        ]);
    }
}
