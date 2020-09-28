<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\Model\Collection\MetaCollection;

class Payment
{
    /**
     * The payment method.
     *
     * @var  string|null
     */
    public $method;

    /**
     * The payment method title.
     *
     * @var  string|null
     */
    public $method_title;

    /**
     * The payment transation identificator.
     *
     * @var  string|null
     */
    public $transaction_id;

    /**
     * The payment constructor.
     *
     * @param  \Corcel\Model\Collection\MetaCollection<string>  $meta
     */
    public function __construct(MetaCollection $meta)
    {
        $this->method         = $meta->_payment_method;
        $this->method_title   = $meta->_payment_method_title;
        $this->transaction_id = $meta->_transaction_id;
    }
}
