<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Model\User;
use Corcel\WooCommerce\Traits\AddressesTrait;

class Customer extends User
{
    use AddressesTrait;

    /**
     * @var array
     */
    protected $appends = [
        'billing',
        'shipping',
    ];
}
