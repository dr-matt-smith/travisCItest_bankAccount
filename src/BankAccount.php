<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 08/03/2016
 * Time: 09:26
 */

namespace Itb;


class BankAccount
{
    private $firstName;
    private $surname;
    private $openingBalance;
    private $balance = 0;
    private $lastError = null;
    private $overdraftLimit = 0;
    private $interestRate = 0;

    /**
     * @return int
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * @param int $interestRate
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;
    }

    /**
     * @return int
     */
    public function getOverdraftLimit()
    {
        return $this->overdraftLimit;
    }

    /**
     * @param int $overdraftLimit
     */
    public function setOverdraftLimit($overdraftLimit)
    {
        if($overdraftLimit < 0){
            $this->overdraftLimit = $overdraftLimit;
        } else {
            $this->lastError = 'positive overdraft limit';
        }
    }

    /**
     * @return null
     */
    public function getLastError()
    {
        return $this->lastError;
    }


    public function __construct($firstName, $surname, $openingBalance)
    {
        $this->firstName = $firstName;
        $this->surname = $surname;
        $this->openingBalance = $openingBalance;
        $this->balance = $openingBalance;
    }

    /**
     * @return mixed
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }


    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function deposit($sum)
    {
        if($sum >= 0){
            $this->balance += $sum;
            return $this->balance;
        } else {
            $this->lastError = 'negative deposit';
            return false;
        }
    }

    public function withdraw($sum)
    {
        if($sum >= 0){
            if($sum <= $this->getMaxWithdrawal()){
                $this->balance -= $sum;
                return $this->balance;
            } else {
                $this->lastError = 'insufficient funds for withdrawal';
                return false;
            }
        } else {
            return false;
        }
    }

    public function getMaxWithdrawal()
    {
        return -1 * ($this->overdraftLimit - $this->balance);
    }

    public function calculateOverdraftAnnualInterest()
    {
        return -1 * ($this->interestRate / 100) * $this->balance;
    }

}