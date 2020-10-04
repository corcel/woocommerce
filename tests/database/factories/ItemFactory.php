<?php
declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Corcel\WooCommerce\Model\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'order_item_name' => $faker->words(mt_rand(2, 4), true),
        'order_item_type' => $faker->randomElement(['line_item', 'tax', 'coupon']),
        'order_id'        => $faker->numberBetween(1, 10000),
    ];
});
