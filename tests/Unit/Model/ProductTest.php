<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_price_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_price', '9.99');

        $this->assertSame('9.99', $product->price);
    }

    public function test_regular_price_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_regular_price', '8.88');

        $this->assertSame('8.88', $product->regular_price);
    }

    public function test_sale_price_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_sale_price', '7.77');

        $this->assertSame('7.77', $product->sale_price);
    }

    public function test_on_sale_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_regular_price', '6.66');
        $product->createMeta('_sale_price', '5.55');

        $this->assertTrue($product->on_sale);

        $product = $this->createProduct();
        $product->createMeta('_regular_price', '4.44');

        $this->assertFalse($product->on_sale);
    }

    public function test_sku_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_sku', 'UNIQUE');

        $this->assertSame('UNIQUE', $product->sku);
    }

    public function test_tax_status_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_tax_status', 'taxable');

        $this->assertSame('taxable', $product->tax_status);
    }

    public function test_is_taxable_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_tax_status', 'taxable');

        $this->assertTrue($product->is_taxable);

        $product = $this->createProduct();
        $product->createMeta('_tax_status', '');

        $this->assertFalse($product->is_taxable);
    }

    public function test_weight_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_weight', '3.33');

        $this->assertSame('3.33', $product->weight);
    }

    public function test_length_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_length', '2.22');

        $this->assertSame('2.22', $product->length);
    }

    public function test_width_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_width', '1.11');

        $this->assertSame('1.11', $product->width);
    }

    public function test_height_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_height', '0.00');

        $this->assertSame('0.00', $product->height);
    }

    public function test_is_virtual_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_virtual', 'yes');

        $this->assertTrue($product->is_virtual);

        $product = $this->createProduct();
        $product->createMeta('_virtual', 'no');

        $this->assertFalse($product->is_virtual);
    }

    public function test_is_downloadable_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_downloadable', 'yes');

        $this->assertTrue($product->is_downloadable);

        $product = $this->createProduct();
        $product->createMeta('_downloadable', 'no');

        $this->assertFalse($product->is_downloadable);
    }

    public function test_stock_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_stock', '5');

        $this->assertSame('5', $product->stock);
    }

    public function test_in_stock_property(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_stock_status', 'instock');

        $this->assertTrue($product->in_stock);

        $product = $this->createProduct();
        $product->createMeta('_stock_status', '');

        $this->assertFalse($product->in_stock);
    }

    public function test_crosssells_property(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Product> */
        $crosssellProducts = Product::factory()->count(2)->create();

        $product = $this->createProduct();
        $product->createMeta('_crosssell_ids', serialize($crosssellProducts->pluck('ID')->toArray()));

        $this->assertSame(2, $product->crosssells->count());
        // @phpstan-ignore-next-line
        $this->assertTrue($product->crosssells->first()->is($crosssellProducts->first()));
    }

    public function test_empty_crosssells(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_crosssell_ids', serialize([]));

        $this->assertSame(0, $product->crosssells->count());
    }

    public function test_upsells_property(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Product> */
        $upsellProducts = Product::factory()->count(3)->create();

        $product = $this->createProduct();
        $product->createMeta('_upsell_ids', serialize($upsellProducts->pluck('ID')->toArray()));

        $this->assertSame(3, $product->upsells->count());
        // @phpstan-ignore-next-line
        $this->assertTrue($product->upsells->first()->is($upsellProducts->first()));
    }

    public function test_empty_upsells(): void
    {
        $product = $this->createProduct();
        $product->createMeta('_upsell_ids', serialize([]));

        $this->assertSame(0, $product->upsells->count());
    }

    public function test_related_items(): void
    {
        $product = $this->createProduct();

        /** @var Item */
        $firstItem = Item::factory()->create();
        $firstItem->createMeta('_product_id', $product->ID);

        /** @var Item */
        $secondItem = Item::factory()->create();
        $secondItem->createMeta('_product_id', $product->ID);

        $this->assertSame(2, $product->items->count());
        // @phpstan-ignore-next-line
        $this->assertTrue($product->items->first()->is($firstItem));
    }

    private function createProduct(): Product
    {
        /** @var Product */
        $product = Product::factory()->create();

        return $product;
    }
}
