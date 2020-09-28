<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Model\Taxonomy;

class ProductCategory extends Taxonomy
{
    /**
     * The taxonomy slug.
     *
     * @var  string
     */
    protected $taxonomy = 'product_cat';
}
