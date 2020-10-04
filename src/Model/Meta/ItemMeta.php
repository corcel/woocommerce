<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model\Meta;

use Corcel\Model\Meta\PostMeta;

class ItemMeta extends PostMeta
{
    /**
     * @inheritDoc
     *
     * @var  string
     */
    protected $table = 'woocommerce_order_itemmeta';

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $fillable = [
        'order_item_id',
        'meta_key',
        'meta_value',
    ];
}
