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
     * @var  array<string, string>|array<string, array<string, string>>
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
     * @var  array<string>
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
    protected function getLineSubtotalAttribute(): ?string
    {
        $lineSubtotal = $this->getMeta('_line_subtotal');

        return is_scalar($lineSubtotal) ? (string) $lineSubtotal : null;
    }

    /**
     * Get the line subtotal tax attribute.
     *
     * @return  string|null
     */
    protected function getLineSubtotalTaxAttribute(): ?string
    {
        $lineSubtotalTax = $this->getMeta('_line_subtotal_tax');

        return is_scalar($lineSubtotalTax) ? (string) $lineSubtotalTax : null;
    }

    /**
     * Get the line tax attribute.
     *
     * @return  string|null
     */
    protected function getLineTaxAttribute(): ?string
    {
        $lineTax = $this->getMeta('_line_tax');

        return is_scalar($lineTax) ? (string) $lineTax : null;
    }

    /**
     * Get the line total attribute.
     *
     * @return  string|null
     */
    protected function getLineTotalAttribute(): ?string
    {
        $lineTotal = $this->getMeta('_line_total');

        return is_scalar($lineTotal) ? (string) $lineTotal : null;
    }

    /**
     * Get the quantity attribute.
     *
     * @return  string|null
     */
    protected function getQuantityAttribute(): ?string
    {
        $quantity = $this->getMeta('_qty');

        return is_scalar($quantity) ? (string) $quantity : null;
    }

    /**
     * Get the tax class attribute.
     *
     * @return  string|null
     */
    protected function getTaxClassAttribute(): ?string
    {
        $taxClass = $this->getMeta('_tax_class');

        return is_scalar($taxClass) ? (string) $taxClass : null;
    }

    /**
     * @inheritDoc
     *
     * @return  HasMany<ItemMeta>
     */
    public function meta(): HasMany
    {
        return $this->hasMany(ItemMeta::class, 'order_item_id', 'order_item_id');
    }

    /**
     * Get the related order.
     *
     * @return  BelongsTo<Order, Item>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the related product.
     *
     * @return  BelongsTo<Product, Item>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
