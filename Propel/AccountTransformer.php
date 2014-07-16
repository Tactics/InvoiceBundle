<?php

namespace Tactics\InvoiceBundle\Propel;

class AccountTransformer extends Transformer
{
    private $scheme_transformer;    
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $scheme_transformer
     */
    public function __construct($class, Transformer $scheme_transformer)
    {
        $this->scheme_transformer = $scheme_transformer;
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelAccount terug op basis van $account
     * 
     * @param Tactics\InvoiceBundle\Model\Account $account
     * @return \PropelAccount
     */
    public function toOrm($account)
    {
        $propelAccount = parent::toOrm($account);
        
        if ($account->getAccountingScheme())
        {
            $accScheme = $account->getAccountingScheme();            
            $propelAccScheme = $this->scheme_transformer->toOrm($accScheme);
            $propelAccount->setPropelAccountingScheme($propelAccScheme); 
        }            

        return $propelAccount;
    }
    
    /**
     * Geeft domain account terug op basis van $propel_account
     * 
     * @param \ProprlAccount $propel_account
     * @return \Tactics\InvoiceBundle\Propel\Account
     */
    public function fromOrm($propel_account)
    {
        $account = parent::fromOrm($propel_account);
        
        if ($propel_account->getPropelAccountingScheme())
        {
            $propelAccScheme = $propel_account->getPropelAccountingScheme();
            $accScheme = $this->scheme_transformer->fromOrm($propelAccScheme);
            $account->setAccountingScheme($accScheme);
        }           
        
        return $account;
    }      
}

