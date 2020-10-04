<?php
declare(strict_types=1);

namespace Tests\Unit\Support;

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\ShippingAddress;
use InvalidArgumentException;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    private const ORDER_META_FIELDS = [
        '_shipping_first_name' => 'John',
        '_shipping_last_name'  => 'Doe',
        '_shipping_company'    => 'ACME corp.',
        '_shipping_address_1'  => 'Example Street 10',
        '_shipping_address_2'  => 'Test Address',
        '_shipping_city'       => 'Los Angeles',
        '_shipping_state'      => 'California',
        '_shipping_postcode'   => '00000',
        '_shipping_country'    => 'USA',
    ];

    private const CUSTOMER_META_FIELDS = [
        'shipping_first_name' => 'John',
        'shipping_last_name'  => 'Doe',
        'shipping_company'    => 'ACME corp.',
        'shipping_address_1'  => 'Example Street 10',
        'shipping_address_2'  => 'Test Address',
        'shipping_city'       => 'Los Angeles',
        'shipping_state'      => 'California',
        'shipping_postcode'   => '00000',
        'shipping_country'    => 'USA',
    ];

    public function testOrderShippingAddressProperties(): void
    {
        $shippingAddress = $this->createOrderShippingAddress();

        $this->assertSame('John', $shippingAddress->first_name);
        $this->assertSame('Doe', $shippingAddress->last_name);
        $this->assertSame('ACME corp.', $shippingAddress->company);
        $this->assertSame('Example Street 10', $shippingAddress->address_1);
        $this->assertSame('Test Address', $shippingAddress->address_2);
        $this->assertSame('Los Angeles', $shippingAddress->city);
        $this->assertSame('California', $shippingAddress->state);
        $this->assertSame('00000', $shippingAddress->postcode);
        $this->assertSame('USA', $shippingAddress->country);
    }

    public function testCustomerShippingAddressProperties(): void
    {
        $shippingAddress = $this->createCustomerShippingAddress();

        $this->assertSame('John', $shippingAddress->first_name);
        $this->assertSame('Doe', $shippingAddress->last_name);
        $this->assertSame('ACME corp.', $shippingAddress->company);
        $this->assertSame('Example Street 10', $shippingAddress->address_1);
        $this->assertSame('Test Address', $shippingAddress->address_2);
        $this->assertSame('Los Angeles', $shippingAddress->city);
        $this->assertSame('California', $shippingAddress->state);
        $this->assertSame('00000', $shippingAddress->postcode);
        $this->assertSame('USA', $shippingAddress->country);
    }

    public function testToArrayMethod(): void
    {
        $shippingAddress = $this->createOrderShippingAddress();

        $array = [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'company'    => 'ACME corp.',
            'address_1'  => 'Example Street 10',
            'address_2'  => 'Test Address',
            'city'       => 'Los Angeles',
            'state'      => 'California',
            'postcode'   => '00000',
            'country'    => 'USA',
        ];

        $this->assertSame($array, $shippingAddress->toArray());
    }

    public function testToJsonMethod(): void
    {
        $shippingAddress = $this->createOrderShippingAddress();

        $json = '{"first_name":"John","last_name":"Doe","company":"ACME corp.","address_1":"Example Street 10","address_2":"Test Address","city":"Los Angeles","state":"California","postcode":"00000","country":"USA"}';

        $this->assertSame($json, $shippingAddress->toJson());
    }

    public function testInvalidProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $shippingAddress = $this->createOrderShippingAddress();
        $shippingAddress->unknown; // @phpstan-ignore-line
    }

    private function createOrderShippingAddress(): ShippingAddress
    {
        $order = factory(Order::class)->create();
        $order->createMeta(self::ORDER_META_FIELDS);

        return new ShippingAddress($order);
    }

    private function createCustomerShippingAddress(): ShippingAddress
    {
        $customer = factory(Customer::class)->create();
        $customer->createMeta(self::CUSTOMER_META_FIELDS);

        return new ShippingAddress($customer);
    }
}
