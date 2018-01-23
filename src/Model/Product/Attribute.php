<?php

namespace Corcel\WooCommerce\Model\Product;

use Corcel\Model;
use Corcel\Concerns\Aliases as AliasesTrait;

class Attribute extends Model
{
    use AliasesTrait;

    /**
     * @var array
     */
    protected static $aliases = [
        'id'       => 'attribute_id',
        'slug'     => 'attribute_name',
        'name'     => 'attribute_label',
        'type'     => 'attribute_type',
        'order_by' => 'attribute_orderby',
        'public'   => 'attribute_public',
    ];

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
