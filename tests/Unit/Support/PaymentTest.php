<?php
declare(strict_types=1);

namespace Tests\Unit\Support;

use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\Payment;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testProperties(): void
    {
        $payment = $this->createPayment();

        $this->assertSame('test', $payment->method);
        $this->assertSame('Test', $payment->method_title);
        $this->assertSame('tid-000', $payment->transaction_id);
    }

    public function testToArrayMethod(): void
    {
        $payment = $this->createPayment();
        $array   = [
            'method'         => 'test',
            'method_title'   => 'Test',
            'transaction_id' => 'tid-000',
        ];

        $this->assertSame($array, $payment->toArray());
    }

    public function testToJsonMethod(): void
    {
        $payment = $this->createPayment();
        $json    = '{"method":"test","method_title":"Test","transaction_id":"tid-000"}';

        $this->assertSame($json, $payment->toJson());
    }

    public function testInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $order   = factory(Order::class)->create();
        $payment = new class($order) extends Payment {
            public function toArray(): array
            {
                return [
                    fopen('php://input', 'r'),
                ];
            }
        };

        $payment->toJson();
    }

    private function createPayment(): Payment
    {
        $order = factory(Order::class)->create();
        $order->createMeta([
            '_payment_method'       => 'test',
            '_payment_method_title' => 'Test',
            '_transaction_id'       => 'tid-000',
        ]);

        return new Payment($order);
    }
}
