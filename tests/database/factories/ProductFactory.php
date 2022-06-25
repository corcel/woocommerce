<?php
declare(strict_types=1);

namespace Database\Factories;
 
use Corcel\WooCommerce\Model\Product;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $createdAt = DateTimeImmutable::createFromMutable($this->faker->dateTime('now', 'Europe/Warsaw'));
        $createdAtGMT = $createdAt->setTimezone(new DateTimeZone('UTC'));
        $name = $this->faker->sentence;

        return [
            'post_author'           => $this->faker->numberBetween(1, 100),
            'post_date'             => $createdAt->format('Y-m-d H:i:s'),
            'post_date_gmt'         => $createdAtGMT->format('Y-m-d H:i:s'),
            'post_content'          => $this->faker->paragraphs(mt_rand(1, 5), true),
            'post_title'            => $name,
            'post_excerpt'          => $this->faker->paragraph,
            'post_status'           => 'publish',
            'comment_status'        => 'open',
            'ping_status'           => 'closed',
            'post_password'         => '',
            'post_name'             => Str::title($name),
            'to_ping'               => '',
            'pinged'                => '',
            'post_modified'         => $createdAt->format('Y-m-d H:i:s'),
            'post_modified_gmt'     => $createdAtGMT->format('Y-m-d H:i:s'),
            'post_content_filtered' => '',
            'post_parent'           => 0,
            'guid'                  => 'http://woocommerce.example/?post_type=product&p=1',
            'menu_order'            => 0,
            'post_type'             => 'product',
            'post_mime_type'        => '',
            'comment_count'         => 0,
        ];
    }
}