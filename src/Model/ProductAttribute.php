<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Concerns\Aliases;
use Corcel\Model;
use Illuminate\Support\Collection;

/**
 * @property string|null  $id
 * @property string|null  $slug
 * @property string|null  $name
 * @property string|null  $type
 * @property string|null  $order_by
 * @property bool|null    $public
 * @property string|null  $attribute_id
 * @property string|null  $attribute_name
 * @property string|null  $attribute_label
 * @property string|null  $attribute_type
 * @property string|null  $attribute_order_by
 * @property bool|null    $attribute_public
 */
class ProductAttribute extends Model
{
    use Aliases;

    /**
     * The terms collection.
     *
     * @var  \Illuminate\Support\Collection<mixed>
     */
    public $terms;

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

    /**
     * Set the product terms.
     *
     * @param  \Illuminate\Support\Collection<mixed>  $terms
     */
    public function setTerms(Collection $terms): void
    {
        $this->terms = $terms;
    }
}
