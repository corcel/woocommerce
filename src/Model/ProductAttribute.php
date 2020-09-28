<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model;

class ProductAttribute extends Model
{
    use Aliases;

    /**
     * The model aliases.
     *
     * @var  string[]
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
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $casts = [
        'attribute_public' => 'bool',
    ];

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'attribute_id';

    /**
     * @inheritDoc
     */
    protected $table = 'woocommerce_attribute_taxonomies';
}
