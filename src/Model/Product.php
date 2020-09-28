<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model\Attachment;
use Corcel\Model\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

/**
 * @property \Illuminate\Database\Eloquent\Collection  $taxonomies
 * @property \Illuminate\Database\Eloquent\Collection  $product_type
 * @property \Corcel\Model\Collection\MetaCollection   $meta
 * @property \Corcel\Model\Meta\ThumbnailMeta          $thumbnail
 * @property string|null                               $regular_price
 * @property string|null                               $sale_price
 * @property string|null                               $tax_status
 */
class Product extends Post
{
    use Aliases;

    /**
     * The aliases of model.
     *
     * @var  string[][]
     */
    protected static $aliases = [
        'price'         => ['meta' => '_price'],
        'regular_price' => ['meta' => '_regular_price'],
        'sale_price'    => ['meta' => '_sale_price'],
        'sku'           => ['meta' => '_sku'],
        'tax_status'    => ['meta' => '_tax_status'],
        'weight'        => ['meta' => '_weight'],
        'length'        => ['meta' => '_length'],
        'width'         => ['meta' => '_width'],
        'height'        => ['meta' => '_height'],
        'stock'         => ['meta' => '_stock'],
    ];

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'price',
        'regular_price',
        'sale_price',
        'sku',
        'tax_status',
        'weight',
        'length',
        'width',
        'height',
        'virtual',
        'downloadable',
        'stock',
        'in_stock',
        'type',
    ];

    /**
     * The post type of model.
     *
     * @var  string
     */
    protected $postType = 'product';

    /**
     * @intheritDoc
     *
     * @var  string[]
     */
    protected $with = [
        'meta',
        'thumbnail',
        'product_type',
    ];

    /**
     * The related categories.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }

    /**
     * @return \Illuminate\Support\Collection<mixed>
     */
    public function getAttributesAttribute(): BaseCollection
    {
        $attributes = ProductAttribute::all()->keyBy('attribute_name');

        return $this->taxonomies
            ->filter(function ($taxonomy) {
                return strpos($taxonomy->taxonomy, 'pa_') === 0;
            })
            ->groupBy('taxonomy')
            ->map(function ($taxonomy, $taxonomy_name) use ($attributes) {
                $attribute_name = substr($taxonomy_name, 3);
                $attribute = $attributes->get($attribute_name);

                $attribute->setAttribute('terms', $taxonomy->pluck('term'));

                return $attribute;
            })
            ->keyBy('attribute_name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<mixed>
     */
    public function getCrosssellsAttribute(): Collection
    {
        $ids = $this->meta->_crosssell_ids;

        if (empty($ids)) {
            return new Collection();
        }

        $ids = unserialize($ids);

        return static::query()
            ->whereIn('ID', $ids)
            ->get();
    }

    /**
     * @return bool
     */
    public function getDownloadableAttribute()
    {
        return 'yes' === $this->meta->_downloadable;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<mixed>
     */
    public function getGalleryAttribute(): Collection
    {
        $gallery = new Collection([
            $this->thumbnail->attachment,
        ]);

        $attachment_ids = $this->meta->_product_image_gallery;

        if (empty($attachment_ids)) {
            return $gallery;
        }

        $attachment_ids = explode(',', $attachment_ids);
        $attachments    = Attachment::query()->whereIn('ID', $attachment_ids)->get();

        return $gallery->merge($attachments);
    }

    /**
     * @return bool
     */
    public function getInStockAttribute()
    {
        return 'instock' === $this->meta->_stock_status;
    }

    /**
     * @return bool
     */
    public function getIsOnSaleAttribute()
    {
        return !empty($this->sale_price) && $this->sale_price < $this->regular_price;
    }

    /**
     * @return bool
     */
    public function getManageStockAttribute()
    {
        return 'yes' === $this->meta->_manage_stock;
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->product_type->pluck('term.name')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<mixed>
     */
    public function getUpsellsAttribute(): Collection
    {
        $ids = $this->meta->_upsell_ids;

        if (empty($ids)) {
            return new Collection();
        }

        $ids = unserialize($ids);

        return static::query()
            ->whereIn('ID', $ids)
            ->get();
    }

    /**
     * @return bool
     */
    public function getVirtualAttribute()
    {
        return 'yes' === $this->meta->_virtual;
    }

    /**
     * @return bool
     */
    public function isTaxable()
    {
        return 'taxable' === $this->tax_status;
    }

    /**
     * @return mixed
     */
    public function product_type()
    {
        return $this->belongsToMany(
            ProductType::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(
            ProductTag::class,
            'term_relationships',
            'object_id',
            'term_taxonomy_id'
        );
    }
}
