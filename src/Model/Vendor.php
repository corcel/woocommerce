<?php

namespace Corcel\WooCommerce\Model;

use Corcel\Model\Taxonomy;
use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property-read string name
 */
class Vendor extends Taxonomy
{
    use HasFactory;

    protected $taxonomy = 'wcpv_product_vendors';

    /**
     * Create a new factory instance for the model.
     *
     * @return VendorFactory
     */
    protected static function newFactory(): VendorFactory
    {
        return VendorFactory::new();
    }
}