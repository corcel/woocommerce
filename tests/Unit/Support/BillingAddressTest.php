<?php
declare(strict_types=1);

namespace Tests\Unit\Support;

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\BillingAddress;
use InvalidArgumentException;
use Tests\TestCase;

class BillingAddressTest extends TestCase
{
    private const ORDER_META_FIELDS = [
        '_billing_first_name' => 'John',
        '_billing_last_name'  => 'Doe',
        '_billing_company'    => 'ACME corp.',
        '_billing_address_1'  => 'Example Street 10',
        '_billing_address_2'  => 'Test Address',
        '_billing_city'       => 'Los Angeles',
        '_billing_state'      => 'California',
        '_billing_postcode'   => '00000',
        '_billing_country'    => 'USA',
        '_billing_email'      => 'john@doe.com',
        '_billing_phone'      => '00-00-000-000',
    ];

    private const CUSTOMER_META_FIELDS = [
        'billing_first_name' => 'John',
        'billing_last_name'  => 'Doe',
        'billing_company'    => 'ACME corp.',
        'billing_address_1'  => 'Example Street 10',
        'billing_address_2'  => 'Test Address',
        'billing_city'       => 'Los Angeles',
        'billing_state'      => 'California',
        'billing_postcode'   => '00000',
        'billing_country'    => 'USA',
        'billing_email'      => 'john@doe.com',
        'billing_phone'      => '00-00-000-000',
    ];

    public function testOrderBillingAddressProperties(): void
    {
        $billingAddress = $this->createOrderBillingAddress();

        $this->assertSame('John', $billingAddress->first_name);
        $this->assertSame('Doe', $billingAddress->last_name);
        $this->assertSame('ACME corp.', $billingAddress->company);
        $this->assertSame('Example Street 10', $billingAddress->address_1);
        $this->assertSame('Test Address', $billingAddress->address_2);
        $this->assertSame('Los Angeles', $billingAddress->city);
        $this->assertSame('California', $billingAddress->state);
        $this->assertSame('00000', $billingAddress->postcode);
        $this->assertSame('USA', $billingAddress->country);
        $this->assertSame('john@doe.com', $billingAddress->email);
        $this->assertSame('00-00-000-000', $billingAddress->phone);
    }

    public function testCustomerBillingAddressProperties(): void
    {
        $billingAddress = $this->createCustomerBillingAddress();

        $this->assertSame('John', $billingAddress->first_name);
        $this->assertSame('Doe', $billingAddress->last_name);
        $this->assertSame('ACME corp.', $billingAddress->company);
        $this->assertSame('Example Street 10', $billingAddress->address_1);
        $this->assertSame('Test Address', $billingAddress->address_2);
        $this->assertSame('Los Angeles', $billingAddress->city);
        $this->assertSame('California', $billingAddress->state);
        $this->assertSame('00000', $billingAddress->postcode);
        $this->assertSame('USA', $billingAddress->country);
        $this->assertSame('john@doe.com', $billingAddress->email);
        $this->assertSame('00-00-000-000', $billingAddress->phone);
    }

    public function testToArrayMethod(): void
    {
        $billingAddress = $this->createOrderBillingAddress();

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
            'email'      => 'john@doe.com',
            'phone'      => '00-00-000-000',
        ];

        $this->assertSame($array, $billingAddress->toArray());
    }

    public function testToJsonMethod(): void
    {
        $billingAddress = $this->createOrderBillingAddress();

        $json = '{"first_name":"John","last_name":"Doe","company":"ACME corp.","address_1":"Example Street 10","address_2":"Test Address","city":"Los Angeles","state":"California","postcode":"00000","country":"USA","email":"john@doe.com","phone":"00-00-000-000"}';

        $this->assertSame($json, $billingAddress->toJson());
    }

    public function testInvalidProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $billingAddress = $this->createOrderBillingAddress();
        $billingAddress->unknown; // @phpstan-ignore-line
    }

    private function createOrderBillingAddress(): BillingAddress
    {
        $order = factory(Order::class)->create();
        $order->createMeta(self::ORDER_META_FIELDS);

        return new BillingAddress($order);
    }

    private function createCustomerBillingAddress(): BillingAddress
    {
        $customer = factory(Customer::class)->create();
        $customer->createMeta(self::CUSTOMER_META_FIELDS);

        return new BillingAddress($customer);
    }
}
