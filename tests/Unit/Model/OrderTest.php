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
    public function testCurrencyProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_currency', 'USD');

        $this->assertSame('USD', $order->currency);
    }

    public function testTotalProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_total', '9.99');

        $this->assertSame('9.99', $order->total);
    }

    public function testShippingProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_shipping', '8.88');

        $this->assertSame('8.88', $order->shipping);
    }

    public function testTaxProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_tax', '7.77');

        $this->assertSame('7.77', $order->tax);
    }

    public function testShippingTaxProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_order_shipping_tax', '6.66');

        $this->assertSame('6.66', $order->shipping_tax);
    }

    public function testStatusProperty(): void
    {
        $order = $this->createOrder([
            'post_status' => 'wc-refunded',
        ]);

        $this->assertSame('refunded', $order->status);
    }

    public function testDateCompletedProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_date_completed', 1577836800); // '2020-01-01 00:00:00'

        $this->assertInstanceOf(Carbon::class, $order->date_completed);
        $this->assertSame('2020-01-01 00:00:00', $order->date_completed->format('Y-m-d H:i:s')); // @phpstan-ignore-line
    }

    public function testDatePaidProperty(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_date_paid', 1577840400); // '2020-01-01 01:00:00'

        $this->assertInstanceOf(Carbon::class, $order->date_paid);
        $this->assertSame('2020-01-01 01:00:00', $order->date_paid->format('Y-m-d H:i:s')); // @phpstan-ignore-line
    }

    public function testDeprecatedDateFormats(): void
    {
        $order = $this->createOrder();
        $order->createMeta('_completed_date', '2020-01-01 00:00:00');
        $order->createMeta('_paid_date', '2020-01-01 01:00:00');

        $this->assertInstanceOf(Carbon::class, $order->date_completed);
        $this->assertSame('2020-01-01 00:00:00', $order->date_completed->format('Y-m-d H:i:s')); // @phpstan-ignore-line

        $this->assertInstanceOf(Carbon::class, $order->date_paid);
        $this->assertSame('2020-01-01 01:00:00', $order->date_paid->format('Y-m-d H:i:s')); // @phpstan-ignore-line
    }

    public function testPaymentProperty(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(Payment::class, $order->payment);
    }

    public function testBillingAddressProperty(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(BillingAddress::class, $order->billing_address);
    }

    public function testShippingAddressProperty(): void
    {
        $order = $this->createOrder();

        $this->assertInstanceOf(ShippingAddress::class, $order->shipping_address);
    }

    public function testArrayHasAppendedValues(): void
    {
        $order = factory(Order::class)->create();
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

    public function testRelatedCustomer(): void
    {
        /** @var \Corcel\WooCommerce\Model\Customer */
        $customer = factory(Customer::class)->create();

        $order = $this->createOrder();
        $order->createMeta('_customer_user', $customer->ID); // @phpstan-ignore-line

        /** @var \Corcel\WooCommerce\Model\Customer */
        $orderCustomer = $order->customer;

        $this->assertTrue($orderCustomer->is($customer));
    }

    public function testGuestOrder(): void
    {
        $order = $this->createOrder();

        $this->assertNull($order->customer);
    }

    public function testRelatedItems(): void
    {
        $order = $this->createOrder();

        $item = factory(Item::class, 3)->create(['order_id' => $order->ID]); // @phpstan-ignore-line

        $this->assertSame(3, $order->items->count());
    }

    /**
     * @param mixed[]  $attributes
     */
    private function createOrder(array $attributes = []): Order
    {
        return factory(Order::class)->create($attributes);
    }
}
