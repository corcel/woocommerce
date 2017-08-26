<?php

namespace Corcel\WooCommerce\Model\Product;

use Corcel\Model;

class Attribute extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'attribute_public' => 'bool',
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'attribute_id';

    /**
     * @var string
     */
    protected $table = 'woocommerce_attribute_taxonomies';
}
