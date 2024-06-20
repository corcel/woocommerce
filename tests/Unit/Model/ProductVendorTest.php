<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Product;
use Corcel\WooCommerce\Model\Vendor;
use Database\Factories\TermFactory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductVendorTest extends TestCase
{
    public function testProductVendor(): void
    {
        $product = $this->createProduct();
        $vendorName = 'vendor name';
        $vendorTerm = (new TermFactory())->create(['name' => $vendorName]);
        $vendorTax = Vendor::factory()->create(['term_id' => $vendorTerm->term_id]);

        DB::table('term_relationships')->insert([
            'object_id' => $product->ID,
            'term_taxonomy_id' => $vendorTax->term_taxonomy_id,
            'term_order' => 0,
        ]);

        $this->assertModelExists($product->vendor->first());
        $this->assertSame($product->vendor->first()->name, $vendorName);
    }

    private function createProduct(): Product
    {
        /** @var Product */
        $product = Product::factory()->create();

        return $product;
    }
}
