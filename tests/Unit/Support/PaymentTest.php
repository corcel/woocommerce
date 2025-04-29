<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\Payment;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_properties(): void
    {
        $payment = $this->createPayment();

        $this->assertSame('test', $payment->method);
        $this->assertSame('Test', $payment->method_title);
        $this->assertSame('tid-000', $payment->transaction_id);
    }

    public function test_to_array_method(): void
    {
        $payment = $this->createPayment();
        $array = [
            'method' => 'test',
            'method_title' => 'Test',
            'transaction_id' => 'tid-000',
        ];

        $this->assertSame($array, $payment->toArray());
    }

    public function test_to_json_method(): void
    {
        $payment = $this->createPayment();
        $json = '{"method":"test","method_title":"Test","transaction_id":"tid-000"}';

        $this->assertSame($json, $payment->toJson());
    }

    public function test_invalid_json(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var Order */
        $order = Order::factory()->create();
        $payment = new class($order) extends Payment
        {
            public function toArray(): array
            {
                return [
                    'invalid' => fopen('php://input', 'r'),
                ];
            }
        };

        $payment->toJson();
    }

    private function createPayment(): Payment
    {
        /** @var Order */
        $order = Order::factory()->create();
        $order->createMeta([
            '_payment_method' => 'test',
            '_payment_method_title' => 'Test',
            '_transaction_id' => 'tid-000',
        ]);

        return new Payment($order);
    }
}
