<?php
declare(strict_types=1);

namespace Tests\Unit\Support;

use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\Address;
use InvalidArgumentException;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testInvalidModel(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $model = factory(Item::class)->create();

        new Address($model, 'billing');
    }

    public function testInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $order   = factory(Order::class)->create();
        $address = new class($order, 'billing') extends Address {
            public function toArray(): array
            {
                return [
                    fopen('php://input', 'r'),
                ];
            }
        };

        $address->toJson();
    }
}
