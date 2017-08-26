<?php

namespace Corcel\WooCommerce;

use Corcel\Model\Collection\MetaCollection;

class Payment
{
    /**
     * @param MetaCollection $meta
     */
    public function __construct(MetaCollection $meta)
    {
        $this->method         = $meta->_payment_method;
        $this->title          = $meta->_payment_method_title;
        $this->transaction_id = $meta->_transaction_id;
    }
}
