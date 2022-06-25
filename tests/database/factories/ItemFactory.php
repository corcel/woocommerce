<?php

declare(strict_types=1);

namespace Database\Factories;

use Corcel\WooCommerce\Model\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_item_name' => $this->faker->words(mt_rand(2, 4), true),
            'order_item_type' => $this->faker->randomElement(['line_item', 'tax', 'coupon']),
            'order_id'        => $this->faker->numberBetween(1, 10000),
        ];
    }
}
