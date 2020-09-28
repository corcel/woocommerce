<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Model\User;
use Corcel\WooCommerce\Traits\AddressesTrait;

/**
 * @property \Corcel\Model\Collection\MetaCollection   $meta
 */
class Customer extends User
{
    use AddressesTrait;

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'billing',
        'shipping',
    ];
}
