<?php
declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Corcel\WooCommerce\Model\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    $name = $faker->firstName;

    return [
        'user_login'          => Str::lower(Str::ascii($name)),
        'user_pass'           => bcrypt('secret'),
        'user_nicename'       => $name,
        'user_email'          => $faker->email,
        'user_url'            => $faker->url,
        'user_registered'     => $faker->dateTime,
        'user_activation_key' => Str::random(10),
        'user_status'         => 0,
        'display_name'        => $name,
    ];
});
