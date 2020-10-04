<?php
declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Corcel\WooCommerce\Model\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $createdAt = DateTimeImmutable::createFromMutable($faker->dateTime('now', 'Europe/Warsaw'));
    $createdAtGMT = $createdAt->setTimezone(new DateTimeZone('UTC'));

    return [
        'post_author'           => $faker->numberBetween(1, 100),
        'post_date'             => $createdAt->format('Y-m-d H:i:s'),
        'post_date_gmt'         => $createdAtGMT->format('Y-m-d H:i:s'),
        'post_content'          => '',
        'post_title'            => sprintf('Order &ndash; %s', $createdAt->format('F j, Y @ h:i A')),
        'post_excerpt'          => '',
        'post_status'           => 'wc-completed',
        'comment_status'        => 'closed',
        'ping_status'           => 'closed',
        'post_password'         => '',
        'post_name'             => '',
        'to_ping'               => '',
        'pinged'                => '',
        'post_modified'         => $createdAt->format('Y-m-d H:i:s'),
        'post_modified_gmt'     => $createdAtGMT->format('Y-m-d H:i:s'),
        'post_content_filtered' => '',
        'post_parent'           => 0,
        'guid'                  => 'http://woocommerce.example/?post_type=shop_order&p=1',
        'menu_order'            => 0,
        'post_type'             => 'shop_order',
        'post_mime_type'        => '',
        'comment_count'         => 0,
    ];
});

$factory->state(Order::class, 'pending', [
    'post_status' => 'wc-pending',
]);

$factory->state(Order::class, 'cancelled', [
    'post_status' => 'wc-cancelled',
]);

$factory->state(Order::class, 'refunded', [
    'post_status' => 'wc-refunded',
]);

$factory->state(Order::class, 'withMeta', []);
$factory->state(Order::class, 'withShipping', []);
$factory->state(Order::class, 'paid', []);

$factory->afterCreatingState(Order::class, 'withMeta', function (Order $order, Faker $faker) {
    $order->createMeta([
        '_order_currency'     => $faker->currencyCode,
        '_order_total'        => $faker->randomFloat(2, 0, 200),
        '_order_tax'          => $faker->randomFloat(2, 0, 200),
        '_date_completed'     => $faker->dateTime->format('Y-m-d H:i:s'),
        '_billing_first_name' => $faker->firstName,
        '_billing_last_name'  => $faker->lastName,
        '_billing_company'    => $faker->optional()->company,
        '_billing_address_1'  => $faker->streetAddress,
        '_billing_address_2'  => $faker->optional()->secondaryAddress,
        '_billing_city'       => $faker->city,
        '_billing_state'      => $faker->state,
        '_billing_postcode'   => $faker->postcode,
        '_billing_country'    => $faker->country,
        '_billing_email'      => $faker->email,
        '_billing_phone'      => $faker->phoneNumber,
    ]);
});

$factory->afterCreatingState(Order::class, 'withShipping', function (Order $order, Faker $faker) {
    $order->createMeta([
        '_order_shipping'      => $faker->randomFloat(2, 0, 200),
        '_order_shipping_tax'  => $faker->randomFloat(2, 0, 200),
        '_shipping_first_name' => $faker->firstName,
        '_shipping_last_name'  => $faker->lastName,
        '_shipping_company'    => $faker->optional()->company,
        '_shipping_address_1'  => $faker->streetAddress,
        '_shipping_address_2'  => $faker->optional()->secondaryAddress,
        '_shipping_city'       => $faker->city,
        '_shipping_state'      => $faker->state,
        '_shipping_postcode'   => $faker->postcode,
        '_shipping_country'    => $faker->country,
    ]);
});

$factory->afterCreatingState(Order::class, 'paid', function (Order $order, Faker $faker) {
    $paymentMethod = $faker->word;

    $order->createMeta([
        '_date_paid'            => $faker->dateTime->format('Y-m-d H:i:s'),
        '_payment_method'       => $paymentMethod,
        '_payment_method_title' => ucfirst($paymentMethod),
        '_transaction_id'       => $faker->asciify('*******'),
    ]);
});
