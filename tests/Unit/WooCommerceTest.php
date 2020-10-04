<?php
declare(strict_types=1);

namespace Tests\Unit;

use Corcel\WooCommerce\WooCommerce;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WooCommerceTest extends TestCase
{
    public function testShopCurrency(): void
    {
        DB::table('options')->insert([
            'option_name'  => 'woocommerce_currency',
            'option_value' => 'EUR',
        ]);

        $this->assertSame('EUR', WooCommerce::currency());
    }
}
