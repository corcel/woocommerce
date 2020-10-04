<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Concerns\MetaFields;
use Corcel\Model;
use Corcel\WooCommerce\Model\Meta\ItemMeta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                                $id
 * @property string                             $name
 * @property string                             $type
 * @property int                                $order_item_id
 * @property string                             $order_item_name
 * @property string                             $order_item_type
 * @property int                                $order_id
 * @property string|null                        $product_id
 * @property string|null                        $variation_id
 * @property string|null                        $quantity
 * @property string|null                        $tax_class
 * @property string|null                        $line_subtotal
 * @property string|null                        $line_subtotal_tax
 * @property string|null                        $line_total
 * @property string|null                        $line_tax
 * @property \Corcel\WooCommerce\Model\Order    $order
 * @property \Corcel\WooCommerce\Model\Product  $product
 */
class Item extends Model
{
    use Aliases;
    use MetaFields;

    /**
     * The model aliases.
     *
     * @var  string[][]|string[]
     */
    protected static $aliases = [
        'id'           => 'order_item_id',
        'name'         => 'order_item_name',
        'type'         => 'order_item_type',
        'product_id'   => ['meta' => '_product_id'],
        'variation_id' => ['meta' => '_variation_id'],
    ];

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'quantity',
        'tax_class',
        'line_subtotal',
        'line_subtotal_tax',
        'line_total',
        'line_tax',
    ];

    /**
     * @inheritDoc
     *
     * @var  string
     */
    protected $table = 'woocommerce_order_items';

    /**
     * @inheritDoc
     *
     * @var  string
     */
    protected $primaryKey = 'order_item_id';

    /**
     * @inheritDoc
     *
     * @var  bool
     */
    public $timestamps = false;

    /**
     * Get the line subtotal attribute.
     *
     * @return  string|null
     */
    protected function getLineSubtotalAttribute()
    {
        return $this->getMeta('_line_subtotal');
    }

    /**
     * Get the line subtotal tax attribute.
     *
     * @return  string|null
     */
    protected function getLineSubtotalTaxAttribute()
    {
        return $this->getMeta('_line_subtotal_tax');
    }

    /**
     * Get the line tax attribute.
     *
     * @return  string|null
     */
    protected function getLineTaxAttribute()
    {
        return $this->getMeta('_line_tax');
    }

    /**
     * Get the line total attribute.
     *
     * @return  string|null
     */
    protected function getLineTotalAttribute()
    {
        return $this->getMeta('_line_total');
    }

    /**
     * Get the quantity attribute.
     *
     * @return  string|null
     */
    protected function getQuantityAttribute()
    {
        return $this->getMeta('_qty');
    }

    /**
     * Get the tax class attribute.
     *
     * @return  string|null
     */
    protected function getTaxClassAttribute()
    {
        return $this->getMeta('_tax_class');
    }

    /**
     * @inheritDoc
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta(): HasMany
    {
        return $this->hasMany(ItemMeta::class, 'order_item_id', 'order_item_id');
    }

    /**
     * Get the related order.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the related product.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
