<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Model\Product;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function test_line_subtotal_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_subtotal', '9.99');

        $this->assertSame('9.99', $item->line_subtotal);
    }

    public function test_line_subtotal_tax_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_subtotal_tax', '8.88');

        $this->assertSame('8.88', $item->line_subtotal_tax);
    }

    public function test_line_tax_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_tax', '7.77');

        $this->assertSame('7.77', $item->line_tax);
    }

    public function test_line_total_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_total', '6.66');

        $this->assertSame('6.66', $item->line_total);
    }

    public function test_quantity_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_qty', '5');

        $this->assertSame('5', $item->quantity);
    }

    public function test_tax_class_property(): void
    {
        $item = $this->createItem();
        $item->createMeta('_tax_class', 'standard');

        $this->assertSame('standard', $item->tax_class);
    }

    public function test_related_order(): void
    {
        /** @var Order */
        $order = Order::factory()->create();
        $item = $this->createItem(['order_id' => $order->ID]);

        $this->assertTrue($item->order->is($order));
    }

    public function test_related_product(): void
    {
        /** @var \Corcel\WooCommerce\Model\Product */
        $product = Product::factory()->create();

        $item = $this->createItem();
        $item->createMeta('_product_id', $product->ID);

        $this->assertTrue($item->product->is($product));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createItem(array $attributes = []): Item
    {
        /** @var Item */
        $item = Item::factory()->create($attributes);

        return $item;
    }
}
