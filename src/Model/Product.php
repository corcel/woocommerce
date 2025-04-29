<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Concerns\MetaFields;
use Corcel\Model\Attachment;
use Corcel\Model\Post;
use Corcel\WooCommerce\Traits\HasRelationsThroughMeta;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

/**
 * @property string|null $price
 * @property string|null $regular_price
 * @property string|null $sale_price
 * @property bool $on_sale
 * @property string|null $sku
 * @property string|null $tax_status
 * @property bool $is_taxable
 * @property string|null $weight
 * @property string|null $length
 * @property string|null $width
 * @property string|null $height
 * @property bool $is_virtual
 * @property bool $is_downloadable
 * @property string|null $stock
 * @property bool $in_stock
 * @property string|null $type
 * @property BaseCollection<string, ProductAttribute> $attributes
 * @property Collection<int, Product> $crosssells
 * @property Collection<int, Product> $upsells
 * @property BaseCollection<int, Attachment> $gallery
 * @property Collection<int, ProductCategory> $categories
 * @property Collection<int, Item> $items
 * @property Collection<int, ProductType> $productTypes
 * @property Collection<int, ProductTag> $tags
 */
class Product extends Post
{
    use Aliases;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /** @use HasRelationsThroughMeta<\Illuminate\Database\Eloquent\Model, $this> */
    use HasRelationsThroughMeta;

    use MetaFields;

    /**
     * Preloaded product attributes list.
     *
     * @var Collection<string, ProductAttribute>
     */
    protected static $productAttributes;

    /**
     * {@inheritDoc}
     *
     * @var array<string>
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
     * @var string
     */
    protected $postType = 'product';

    /**
     * {@inheritDoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::$productAttributes = ProductAttribute::all()->keyBy('attribute_name');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return ProductFactory
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    /**
     * Get the price attribute.
     */
    protected function getPriceAttribute(): ?string
    {
        $price = $this->getMeta('_price');

        return is_scalar($price) ? (string) $price : null;
    }

    /**
     * Get the regular price attribute.
     */
    protected function getRegularPriceAttribute(): ?string
    {
        $regularPrice = $this->getMeta('_regular_price');

        return is_scalar($regularPrice) ? (string) $regularPrice : null;
    }

    /**
     * Get the sale price attribute
     */
    protected function getSalePriceAttribute(): ?string
    {
        $salePrice = $this->getMeta('_sale_price');

        return is_scalar($salePrice) ? (string) $salePrice : null;
    }

    /**
     * Get the on sale attribute
     */
    protected function getOnSaleAttribute(): bool
    {
        return ! empty($this->sale_price) && $this->sale_price < $this->regular_price;
    }

    /**
     * Get the SKU attribute.
     */
    protected function getSkuAttribute(): ?string
    {
        $sku = $this->getMeta('_sku');

        return is_scalar($sku) ? (string) $sku : null;
    }

    /**
     * Get the tax status attribute.
     */
    protected function getTaxStatusAttribute(): ?string
    {
        $taxStatus = $this->getMeta('_tax_status');

        return is_scalar($taxStatus) ? (string) $taxStatus : null;
    }

    /**
     * Get the is taxable attribute.
     */
    public function getIsTaxableAttribute(): bool
    {
        return $this->tax_status === 'taxable';
    }

    /**
     * Get the weight attribute.
     */
    protected function getWeightAttribute(): ?string
    {
        $weight = $this->getMeta('_weight');

        return is_scalar($weight) ? (string) $weight : null;
    }

    /**
     * Get the length attribute.
     */
    protected function getLengthAttribute(): ?string
    {
        $length = $this->getMeta('_length');

        return is_scalar($length) ? (string) $length : null;
    }

    /**
     * Get the width attribute.
     */
    protected function getWidthAttribute(): ?string
    {
        $width = $this->getMeta('_width');

        return is_scalar($width) ? (string) $width : null;
    }

    /**
     * Get the height attribute.
     */
    protected function getHeightAttribute(): ?string
    {
        $height = $this->getMeta('_height');

        return is_scalar($height) ? (string) $height : null;
    }

    /**
     * Get the is virtual attribute.
     */
    protected function getIsVirtualAttribute(): bool
    {
        return $this->getMeta('_virtual') === 'yes';
    }

    /**
     * Get the is downloadable attribute.
     */
    protected function getIsDownloadableAttribute(): bool
    {
        return $this->getMeta('_downloadable') === 'yes';
    }

    /**
     * Get the stock attribute.
     */
    protected function getStockAttribute(): ?string
    {
        $stock = $this->getMeta('_stock');

        return is_scalar($stock) ? (string) $stock : null;
    }

    /**
     * Get the in stock attribute.
     */
    protected function getInStockAttribute(): bool
    {
        return $this->getMeta('_stock_status') === 'instock';
    }

    /**
     * Get the product attributes attribute.
     *
     * @return BaseCollection<string, ProductAttribute>
     */
    protected function getAttributesAttribute(): BaseCollection
    {
        $taxonomies = $this->taxonomies;

        return $taxonomies
            ->filter(function ($taxonomy) {
                return isset($taxonomy->taxonomy)
                    && is_string($taxonomy->taxonomy)
                    && Str::startsWith($taxonomy->taxonomy, 'pa_');
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
     * @return Collection<int, Product>
     */
    protected function getCrosssellsAttribute(): Collection
    {
        $crosssells = $this->getMeta('_crosssell_ids');

        if (! is_string($crosssells)) {
            return static::newCollection();
        }

        $crosssells = unserialize($crosssells);

        if (empty($crosssells)) {
            return static::newCollection();
        }

        /** @var Collection<int, Product> */
        return static::query()->whereIn('ID', $crosssells)->get();
    }

    /**
     * Get the up-sells attribute.
     *
     * @return Collection<int, Product>
     */
    public function getUpsellsAttribute(): Collection
    {
        $upsells = $this->getMeta('_upsell_ids');

        if (! is_string($upsells)) {
            return static::newCollection();
        }

        $upsells = unserialize($upsells);

        if (empty($upsells)) {
            return static::newCollection();
        }

        /** @var Collection<int, Product> */
        return static::query()->whereIn('ID', $upsells)->get();
    }

    /**
     * Get the gallery attribute.
     *
     * @return BaseCollection<int, Attachment>
     */
    public function getGalleryAttribute(): BaseCollection
    {
        $gallery = new BaseCollection;

        if (isset($this->thumbnail->attachment) && $this->thumbnail->attachment instanceof Attachment) {
            $gallery->push($this->thumbnail->attachment);
        }

        $attachmentsId = $this->getMeta('_product_image_gallery');

        if (! is_string($attachmentsId) || empty($attachmentsId)) {
            return $gallery;
        }

        $attachmentsId = explode(',', $attachmentsId);
        $attachments = Attachment::query()->whereIn('ID', $attachmentsId)->get();

        return $gallery->merge($attachments);
    }

    /**
     * Get the type attribute.
     */
    protected function getTypeAttribute(): ?string
    {
        /** @var BaseCollection<int, string> */
        $productTypeNames = $this->productTypes->pluck('term.name');

        return $productTypeNames->first();
    }

    /**
     * Get the related categories.
     *
     * @return BelongsToMany<ProductCategory, $this>
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
     * @return HasMany<\Illuminate\Database\Eloquent\Model, $this>
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
     * @return BelongsToMany<ProductType, $this>
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
     * @return BelongsToMany<ProductTag, $this>
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
