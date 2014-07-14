<?php

namespace Tactics\InvoiceBundle\Propel;

class GlAccountTransformer extends Transformer
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
     * Geeft PropelGlAccount terug op basis van $gl_account
     * 
     * @param Tactics\InvoiceBundle\Model\GlAccount $gl_account
     * @return \PropelGlAccount
     */
    public function toOrm($gl_account)
    {
        $propelGlAccount = parent::toOrm($gl_account);
        
        if ($gl_account->getAccountingScheme())
        {
            $accScheme = $gl_account->getAccountingScheme();            
            $propelAccScheme = $this->scheme_transformer->toOrm($accScheme);
            $propelGlAccount->setPropelAccountingScheme($propelAccScheme); 
        }            

        return $propelGlAccount;
    }
    
    /**
     * Geeft domain gl_account terug op basis van $propel_gl_account
     * 
     * @param \ProprlGlAccount $propel_gl_account
     * @return \Tactics\InvoiceBundle\Propel\GlAccount
     */
    public function fromOrm($propel_gl_account)
    {
        $gl_account = parent::fromOrm($propel_gl_account);
        
        if ($propel_gl_account->getPropelAccountingScheme())
        {
            $propelAccScheme = $propel_gl_account->getPropelAccountingScheme();
            $accScheme = $this->scheme_transformer->fromOrm($propelAccScheme);
            $gl_account->setAccountingScheme($accScheme);
        }           
        
        return $gl_account;
    }      
}

