<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Model\Taxonomy;

class ProductType extends Taxonomy
{
    /**
     * The taxonomy slug.
     *
     * @var  string
     */
    protected $taxonomy = 'product_type';
}
