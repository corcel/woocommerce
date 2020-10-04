<?php
declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testOrderCountProperty(): void
    {
        $customer = $this->createCustomer();
        $customer->createMeta([
            '_order_count' => 10,
        ]);

        $this->assertSame(10, $customer->order_count);
    }

    public function testArrayHasAppendedValues(): void
    {
        $customer = $this->createCustomer();

        $this->assertArrayHasKey('order_count', $customer->toArray());
    }

    public function testRelatedOrders(): void
    {
        $customer = $this->createCustomer();

        /** @var \Corcel\WooCommerce\Model\Order */
        $order = factory(Order::class)->create();
        $order->createMeta('_customer_user', $customer->ID); // @phpstan-ignore-line

        $this->assertTrue($customer->orders()->get()->first()->is($order));

        /** @var \Corcel\WooCommerce\Model\Order */
        $order = factory(Order::class)->create();
        $order->createMeta('_customer_user', $customer->ID); // @phpstan-ignore-line

        $this->assertSame(2, $customer->orders()->count());
    }

    private function createCustomer(): Customer
    {
        return factory(Customer::class)->create();
    }
}
