<?php
declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Model\Product;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function testLineSubtotalProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_subtotal', '9.99');

        $this->assertSame('9.99', $item->line_subtotal);
    }

    public function testLineSubtotalTaxProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_subtotal_tax', '8.88');

        $this->assertSame('8.88', $item->line_subtotal_tax);
    }

    public function testLineTaxProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_tax', '7.77');

        $this->assertSame('7.77', $item->line_tax);
    }

    public function testLineTotalProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_line_total', '6.66');

        $this->assertSame('6.66', $item->line_total);
    }

    public function testQuantityProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_qty', '5');

        $this->assertSame('5', $item->quantity);
    }

    public function testTaxClassProperty(): void
    {
        $item = $this->createItem();
        $item->createMeta('_tax_class', 'standard');

        $this->assertSame('standard', $item->tax_class);
    }

    public function testRelatedOrder(): void
    {
        $order = factory(Order::class)->create();
        $item  = $this->createItem(['order_id' => $order->ID]);

        $this->assertTrue($item->order->is($order));
    }

    public function testRelatedProduct(): void
    {
        /** @var \Corcel\WooCommerce\Model\Product */
        $product = factory(Product::class)->create();

        $item = $this->createItem();
        $item->createMeta('_product_id', $product->ID); // @phpstan-ignore-line

        $this->assertTrue($item->product->is($product));
    }

    /**
     * @param mixed[]  $attributes
     */
    private function createItem(array $attributes = []): Item
    {
        return factory(Item::class)->create($attributes);
    }
}
