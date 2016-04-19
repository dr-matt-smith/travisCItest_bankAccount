<?php
namespace ItbTest;

use Itb\BankAccount;

class BankAccountTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateObject()
    {
        // Arrange
        $account = new BankAccount(null, null, null);

        // Act

        // Assert
        $this->assertNotNull($account);
    }

    public function testGetFirstNameAfterConstruction()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 'Matt';

        // Act
        $result = $account->getFirstName();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetSurnameAfterConstruction()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 'Smith';

        // Act
        $result = $account->getSurname();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testOpeningBalanceAfterConstruction()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 99;

        // Act
        $result = $account->getOpeningBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testSetGetFirstName()
    {
        // Arrange
        $account = new BankAccount(null, null, null);
        $account->setFirstName('Joe');
        $expectedResult = 'Joe';

        // Act
        $result = $account->getFirstName();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testSetGetSurname()
    {
        // Arrange
        $account = new BankAccount(null, null, null);
        $account->setSurname('Bloggs');
        $expectedResult = 'Bloggs';

        // Act
        $result = $account->getSurname();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetBalanceEqualsOpeningBalance()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 99;

        // Act
        $result = $account->getBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }


    public function testGetBalanceAfterDepositTen()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->deposit(10.00);
        $expectedResult = 109;

        // Act
        $result = $account->getBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetBalanceAfterWithdrawTen()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->withdraw(10.00);
        $expectedResult = 89;

        // Act
        $result = $account->getBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testValidDepositReturnsNewBalance()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 100;

        // Act
        $result = $account->deposit(1);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }


    public function testNegativeDepositLeavesBalanceUnchanged()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->deposit(-1);
        $expectedResult = 99;

        // Act
        $result = $account->getBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testNegativeDepositSetsLastErrorToNegativeDeposit()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->deposit(-1);
        $expectedResult = 'negative deposit';

        // Act
        $result = $account->getLastError();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testNegativeDepositReturnsFalse()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);

        // Act
        $result = $account->deposit(-1);

        // Assert
        $this->assertFalse($result);
    }

    public function testNegativeWithdrawalLeavesBalanceUnchanged()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->withdraw(-1);
        $expectedResult = 99;

        // Act
        $result = $account->getBalance();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testNegativeWithdrawalReturnsFalse()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);

        // Act
        $result = $account->withdraw(-1);

        // Assert
        $this->assertFalse($result);
    }

    public function testDefaultOverdraftLimitZero()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 0;

        // Act
        $result = $account->getOverdraftLimit();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testMaxWithdrawalEqualsOverdraftLimitMinusBalance()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = 100;
        $account->setOverdraftLimit(-1);

        // Act
        $result = $account->getMaxWithdrawal();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testSetGetOverdraftLimitValidNegativeValue()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $expectedResult = -99;
        $account->setOverdraftLimit(-99);

        // Act
        $result = $account->getOverdraftLimit();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testSetPositiveOverdraftLimitSetsLastErrorToPositiveOverdraftLimit()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->setOverdraftLimit(88);
        $expectedResult = 'positive overdraft limit';

        // Act
        $result = $account->getLastError();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testWithdrawMoreThanOverdraftLimitSetsLastErrorToInsufficientFunds()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 99.00);
        $account->withdraw(200);
        $expectedResult = 'insufficient funds for withdrawal';

        // Act
        $result = $account->getLastError();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testValidAnnualOverdraftInterest()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 0);
        $account->setOverdraftLimit(-99);
        $account->withdraw(50);
        $account->setInterestRate(10);
        $expectedResult = (10 / 100 * 50);

        // Act
        $result = $account->calculateOverdraftAnnualInterest();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public function testZeroAnnualOverdraftInterestReturnsZero()
    {
        // Arrange
        $account = new BankAccount('Matt', 'Smith', 0);
        $account->setOverdraftLimit(-99);
        $account->withdraw(50);
        $account->setInterestRate(0);
        $expectedResult = 0;

        // Act
        $result = $account->calculateOverdraftAnnualInterest();

        // Assert
        $this->assertEquals($expectedResult, $result);
    }
}
