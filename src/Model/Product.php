<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Concerns\MetaFields;
use Corcel\Model\Attachment;
use Corcel\Model\Post;
use Corcel\WooCommerce\Traits\HasRelationsThroughMeta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

/**
 * @property string|null                               $price
 * @property string|null                               $regular_price
 * @property string|null                               $sale_price
 * @property bool                                      $on_sale
 * @property string|null                               $sku
 * @property string|null                               $tax_status
 * @property bool                                      $is_taxable
 * @property string|null                               $weight
 * @property string|null                               $length
 * @property string|null                               $width
 * @property string|null                               $height
 * @property bool                                      $is_virtual
 * @property bool                                      $is_downloadable
 * @property string|null                               $stock
 * @property bool                                      $in_stock
 * @property string|null                               $type
 * @property \Illuminate\Support\Collection            $attributes
 * @property \Illuminate\Database\Eloquent\Collection  $crosssells
 * @property \Illuminate\Database\Eloquent\Collection  $upsells
 * @property \Illuminate\Support\Collection            $gallery
 * @property \Illuminate\Database\Eloquent\Collection  $categories
 * @property \Illuminate\Database\Eloquent\Collection  $items
 * @property \Illuminate\Database\Eloquent\Collection  $productTypes
 * @property \Illuminate\Database\Eloquent\Collection  $tags
 */
class Product extends Post
{
    use Aliases;
    use MetaFields;
    use HasRelationsThroughMeta;

    /**
     * Preloaded product attributes list.
     *
     * @var  \Illuminate\Database\Eloquent\Collection<\Corcel\WooCommerce\Model\ProductAttribute>
     */
    protected static $productAttributes;

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'price',
        'regular_price',
        'sale_price',
        'on_sale',
        'sku',
        'tax_status',
        'weight',
        'length',
        'width',
        'height',
        'is_virtual',
        'is_downloadable',
        'stock',
        'in_stock',
    ];

    /**
     * The post type of model.
     *
     * @var  string
     */
    protected $postType = 'product';

    /**
     * @inheritDoc
     */
    protected static function boot()
    {
        parent::boot();

        static::$productAttributes = ProductAttribute::all()->keyBy('attribute_name');
    }

    /**
     * Get the price attribute.
     *
     * @return  string|null
     */
    protected function getPriceAttribute(): ?string
    {
        return $this->getMeta('_price');
    }

    /**
     * Get the regular price attribute.
     *
     * @return  string|null
     */
    protected function getRegularPriceAttribute(): ?string
    {
        return $this->getMeta('_regular_price');
    }

    /**
     * Get the sale price attribute
     *
     * @return  string|null
     */
    protected function getSalePriceAttribute(): ?string
    {
        return $this->getMeta('_sale_price');
    }

    /**
     * Get the on sale attribute
     *
     * @return  bool
     */
    protected function getOnSaleAttribute(): bool
    {
        return !empty($this->sale_price) && $this->sale_price < $this->regular_price;
    }

    /**
     * Get the SKU attribute.
     *
     * @return  string|null
     */
    protected function getSkuAttribute(): ?string
    {
        return $this->getMeta('_sku');
    }

    /**
     * Get the tax status attribute.
     *
     * @return  string|null
     */
    protected function getTaxStatusAttribute(): ?string
    {
        return $this->getMeta('_tax_status');
    }

    /**
     * Get the is taxable attribute.
     *
     * @return  bool
     */
    public function getIsTaxableAttribute(): bool
    {
        return 'taxable' === $this->tax_status;
    }

    /**
     * Get the weight attribute.
     *
     * @return  string|null
     */
    protected function getWeightAttribute(): ?string
    {
        return $this->getMeta('_weight');
    }

    /**
     * Get the length attribute.
     *
     * @return  string|null
     */
    protected function getLengthAttribute(): ?string
    {
        return $this->getMeta('_length');
    }

    /**
     * Get the width attribute.
     *
     * @return  string|null
     */
    protected function getWidthAttribute(): ?string
    {
        return $this->getMeta('_width');
    }

    /**
     * Get the height attribute.
     *
     * @return  string|null
     */
    protected function getHeightAttribute(): ?string
    {
        return $this->getMeta('_height');
    }

    /**
     * Get the is virtual attribute.
     *
     * @return  bool
     */
    protected function getIsVirtualAttribute(): bool
    {
        return 'yes' === $this->getMeta('_virtual');
    }

    /**
     * Get the is downloadable attribute.
     *
     * @return  bool
     */
    protected function getIsDownloadableAttribute(): bool
    {
        return 'yes' === $this->getMeta('_downloadable');
    }

    /**
     * Get the stock attribute.
     *
     * @return  string|null
     */
    protected function getStockAttribute(): ?string
    {
        return $this->getMeta('_stock');
    }

    /**
     * Get the in stock attribute.
     *
     * @return  bool
     */
    protected function getInStockAttribute(): bool
    {
        return 'instock' === $this->getMeta('_stock_status');
    }

    /**
     * Get the product attributes attribute.
     *
     * @return  \Illuminate\Support\Collection<\Corcel\WooCommerce\Model\ProductAttribute>
     */
    protected function getAttributesAttribute(): BaseCollection
    {
        $taxonomies = $this->taxonomies; // @phpstan-ignore-line

        return $taxonomies
            ->filter(function ($taxonomy) {
                return Str::startsWith($taxonomy->taxonomy, 'pa_');
            })
            ->groupBy('taxonomy')
            ->map(function ($taxonomy, $taxonomyName) {
                $attributeName = substr($taxonomyName, 3);
                /** @var \Corcel\WooCommerce\Model\ProductAttribute */
                $attribute = static::$productAttributes->get($attributeName);
                $attribute->setTerms($taxonomy->pluck('term'));

                return $attribute;
            })
            ->keyBy('attribute_name');
    }

    /**
     * Get the cross-sells attribute.
     *
     * @return \Illuminate\Database\Eloquent\Collection<mixed>
     */
    protected function getCrosssellsAttribute(): Collection
    {
        $crosssells = $this->getMeta('_crosssell_ids');
        $crosssells = unserialize($crosssells);

        if (empty($crosssells)) {
            return new Collection();
        }

        return static::query()->whereIn('ID', $crosssells)->get();
    }

    /**
     * Get the up-sells attribute.
     *
     * @return \Illuminate\Database\Eloquent\Collection<mixed>
     */
    public function getUpsellsAttribute(): Collection
    {
        $upsells = $this->getMeta('_upsell_ids');
        $upsells = unserialize($upsells);

        if (empty($upsells)) {
            return new Collection();
        }

        return static::query()->whereIn('ID', $upsells)->get();
    }

    /**
     * Get the gallery attribute.
     *
     * @return \Illuminate\Support\Collection<\Corcel\Model\Attachment>
     */
    public function getGalleryAttribute(): BaseCollection
    {
        $thumbnail = $this->thumbnail; // @phpstan-ignore-line
        $gallery   = new BaseCollection([$thumbnail->attachment]);

        $attachmentsId = $this->getMeta('_product_image_gallery');

        if (empty($attachmentsId)) {
            return $gallery;
        }

        $attachmentsId = explode(',', $attachmentsId);
        $attachments   = Attachment::query()->whereIn('ID', $attachmentsId)->get();

        return $gallery->merge($attachments);
    }

    /**
     * Get the type attribute.
     *
     * @return  string|null
     */
    protected function getTypeAttribute(): ?string
    {
        return $this->productTypes->pluck('term.name')->first();
    }

    /**
     * Get the related categories.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }

    /**
     * Get the related items.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasManyThroughMeta(
            Item::class,
            '_product_id',
            'order_item_id',
            'order_item_id'
        );
    }

    /**
     * Get the related product types.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductType::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }

    /**
     * Get the related tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductTag::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }
}
