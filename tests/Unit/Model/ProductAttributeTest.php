<?php
declare(strict_types=1);

namespace Tests\Unit\Model;

use Corcel\WooCommerce\Model\ProductAttribute;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ProductAttributeTest extends TestCase
{
    public function testTermsProperty(): void
    {
        $productAttribute = new ProductAttribute();
        $productAttribute->setTerms(new Collection());

        $this->assertInstanceOf(Collection::class, $productAttribute->terms);
    }
}
