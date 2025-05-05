<?php

declare(strict_types=1);

namespace Database\Factories;

use Corcel\Model\Term;
use Corcel\WooCommerce\Model\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class TermFactory extends Factory
{
    protected $model = Term::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name
        ];
    }
}
