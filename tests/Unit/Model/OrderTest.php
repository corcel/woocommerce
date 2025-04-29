<?php

declare(strict_types=1);

namespace Tests\Unit\Model;

use Carbon\Carbon;
use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\BillingAddress;
use Corcel\WooCommerce\Support\Payment;
use Corcel\WooCommerce\Support\ShippingAddress;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_currency_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_currency', 'USD');

        $this->assertSame('USD', $order->currency);
    }

    public function test_total_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_total', '9.99');

        $this->assertSame('9.99', $order->total);
    }

    public function test_shipping_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_shipping', '8.88');

        $this->assertSame('8.88', $order->shipping);
    }

    public function test_tax_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_tax', '7.77');

        $this->assertSame('7.77', $order->tax);
    }

    public function test_shipping_tax_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_shipping_tax', '6.66');

        $this->assertSame('6.66', $order->shipping_tax);
    }

    public function test_status_property(): void
    {
        $order = $this->createOrder([
            'post_status' => 'wc-refunded',
        ]);

        $this->assertSame('refunded', $order->status);
    }

    public function test_date_completed_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_date_completed', 1577836800); // '2020-01-01 00:00:00'

        $this->assertInstanceOf(Carbon::class, $order->date_completed);
        $this->assertSame('2020-01-01 00:00:00', $order->date_completed->format('Y-m-d H:i:s'));
    }

    public function test_date_paid_property(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_date_paid', 1577840400); // '2020-01-01 01:00:00'

        $this->assertInstanceOf(Carbon::class, $order->date_paid);
        $this->assertSame('2020-01-01 01:00:00', $order->date_paid->format('Y-m-d H:i:s'));
    }

    public function test_deprecated_date_formats(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_completed_date', '2020-01-01 00:00:00');
        $order->createMeta('_paid_date', '2020-01-01 01:00:00');

        $this->assertInstanceOf(Carbon::class, $order->date_completed);
        $this->assertSame('2020-01-01 00:00:00', $order->date_completed->format('Y-m-d H:i:s'));

        $this->assertInstanceOf(Carbon::class, $order->date_paid);
        $this->assertSame('2020-01-01 01:00:00', $order->date_paid->format('Y-m-d H:i:s'));
    }

    public function test_payment_property(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(Payment::class, $order->payment);
    }

    public function test_billing_address_property(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(BillingAddress::class, $order->billing_address);
    }

    public function test_shipping_address_property(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(ShippingAddress::class, $order->shipping_address);
    }

    public function test_array_has_appended_values(): void
    {
        /** @var Order */
        $order = Order::factory()->create();
        $array = $order->toArray();

        $this->assertArrayHasKey('currency', $array);
        $this->assertArrayHasKey('total', $array);
        $this->assertArrayHasKey('shipping', $array);
        $this->assertArrayHasKey('tax', $array);
        $this->assertArrayHasKey('shipping_tax', $array);
        $this->assertArrayHasKey('status', $array);
        $this->assertArrayHasKey('date_completed', $array);
        $this->assertArrayHasKey('date_paid', $array);
        $this->assertArrayHasKey('payment', $array);
        $this->assertArrayHasKey('billing_address', $array);
        $this->assertArrayHasKey('shipping_address', $array);
    }

    public function test_related_customer(): void
    {
        /** @var Customer */
        $customer = Customer::factory()->create();

        $order = $this->createOrder();
        $order->createMeta('_customer_user', $customer->ID);

        /** @var Customer */
        $orderCustomer = $order->customer;

        $this->assertTrue($orderCustomer->is($customer));
    }

    public function test_guest_order(): void
    {
        $order = $this->createOrder();

        $this->assertNull($order->customer);
    }

    public function test_related_items(): void
    {
        $order = $this->createOrder();

        $item = Item::factory()->count(3)->create(['order_id' => $order->ID]);

        $this->assertSame(3, $order->items->count());
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createOrder(array $attributes = []): Order
    {
        /** @var Order */
        $order = Order::factory()->create($attributes);

        return $order;
    }
}
