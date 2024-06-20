<?php

declare(strict_types=1);

namespace Database\Factories;

use Corcel\WooCommerce\Model\Product;
use Corcel\WooCommerce\Model\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends Factory<Product>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'taxonomy' => 'wcpv_product_vendors',
            'description' => '',
        ];
    }
}
