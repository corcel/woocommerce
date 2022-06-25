<?php
declare(strict_types=1);

namespace Database\Factories;
 
use Corcel\WooCommerce\Model\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->firstName;

        return [
            'user_login'          => Str::lower(Str::ascii($name)),
            'user_pass'           => bcrypt('secret'),
            'user_nicename'       => $name,
            'user_email'          => $this->faker->email,
            'user_url'            => $this->faker->url,
            'user_registered'     => $this->faker->dateTime,
            'user_activation_key' => Str::random(10),
            'user_status'         => 0,
            'display_name'        => $name,
        ];
    }
}