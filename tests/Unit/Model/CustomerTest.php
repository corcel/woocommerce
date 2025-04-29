<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_order_count_property(): void
    {
        $customer = $this->createCustomer();
        $customer->createMeta([
            '_order_count' => 10,
        ]);

        $this->assertSame(10, $customer->order_count);
    }

    public function test_array_has_appended_values(): void
    {
        $customer = $this->createCustomer();

        $this->assertArrayHasKey('order_count', $customer->toArray());
    }

    public function test_related_orders(): void
    {
        $customer = $this->createCustomer();

        /** @var Order */
        $order = Order::factory()->create();
        $order->createMeta('_customer_user', $customer->ID);

        $this->assertTrue($customer->orders()->get()->first()->is($order)); // @phpstan-ignore-line

        /** @var Order */
        $order = Order::factory()->create();
        $order->createMeta('_customer_user', $customer->ID);

        $this->assertSame(2, $customer->orders()->count());
    }

    private function createCustomer(): Customer
    {
        /** @var Customer */
        return Customer::factory()->create();
    }
}
