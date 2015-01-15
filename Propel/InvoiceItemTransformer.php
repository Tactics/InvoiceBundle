<?php

namespace Tactics\InvoiceBundle\Propel;

class InvoiceItemTransformer extends Transformer
{
    private $vat_transformer;
    private $account_transformer;
    
    private $account_names = array(
        'gl_account',
        'analytical_1_account',
        'analytical_2_account',
        'analytical_3_account',
        'analytical_4_account',
        'analytical_5_account'
    );
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $vat_transformer
     */
    public function __construct($class, Transformer $vat_transformer, Transformer $account_transformer)
    {
        $this->vat_transformer = $vat_transformer;
        $this->account_transformer = $account_transformer;
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelInvoiceItem terug op basis van $invoice_item
     * 
     * @param Tactics\InvoiceBundle\Model\InvoiceItem $invoice_item
     * @return \PropelInvoiceItem
     */
    public function toOrm($invoice_item)
    {
        $propelInvoiceItem = parent::toOrm($invoice_item);
        
        $vat = $invoice_item->getVat();
        if ($vat)
        {
            $propelInvoiceItem->setPropelVat($this->vat_transformer->toOrm($vat));
        }
        
        foreach ($this->account_names as $account_name)
        {            
            $ucfirstCamelizedAccountName = Helper::camelize($account_name, true);
            $getter = "get{$ucfirstCamelizedAccountName}";
            $account = $invoice_item->$getter();
            if ($account)
            {
                $setter = "setPropelAccountRelatedBy{$ucfirstCamelizedAccountName}Code";
                $propelInvoiceItem->$setter($this->account_transformer->toOrm($account));
            }
        }

        return $propelInvoiceItem;
    }
    
    /**
     * Geeft domain invoice_item terug op basis van $propel_invoice_item
     * 
     * @param \PropelInvoice $propel_invoice_item
     * @return \Tactics\InvoiceBundle\Propel\InvoiceItem
     */
    public function fromOrm($propel_invoice_item)
    {
        $item = parent::fromOrm($propel_invoice_item);
        
        // set vat
        if ($propelVat = $propel_invoice_item->getPropelVat())
        {
            $item->setVat($this->vat_transformer->fromOrm($propelVat));
        }
        
        // set accounts
        foreach ($this->account_names as $account_name)
        {
            $ucfirstCamelizedAccountName = Helper::camelize($account_name, true);
            $propelGetter = "getPropelAccountRelatedBy{$ucfirstCamelizedAccountName}Code";
            $domainSetter = "set{$ucfirstCamelizedAccountName}";
            $account = $propel_invoice_item->$propelGetter();
            $item->$domainSetter($this->account_transformer->fromOrm($account));
        }
        
        return $item;
    }      
}

