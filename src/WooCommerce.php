<?php

namespace Corcel\WooCommerce;

use Corcel\Model\Option;

class WooCommerce
{
    /**
     * @var mixed
     */
    private static $currency;

    public static function currency()
    {
        if (is_null(self::$currency)) {
            self::$currency = Option::get('woocommerce_currency');
        }

        return self::$currency;
    }
}
