<?php

declare(strict_types=1);

namespace Database\Factories;

use Corcel\WooCommerce\Model\Order;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $createdAt = DateTimeImmutable::createFromMutable($this->faker->dateTime('now', 'Europe/Warsaw'));
        $createdAtGMT = $createdAt->setTimezone(new DateTimeZone('UTC'));

        return [
            'post_author'           => $this->faker->numberBetween(1, 100),
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
    }

    /**
     * Applies pending state to model.
     *
     * @return  Factory<Order>
     */
    public function pending(): Factory
    {
        return $this->state(fn () => ['post_status' => 'wc-pending']);
    }

    /**
     * Applies cancelling state to model.
     *
     * @return  Factory<Order>
     */
    public function cancelled(): Factory
    {
        return $this->state(fn () => ['post_status' => 'wc-cancelled']);
    }

    /**
     * Applies refunded state to model.
     *
     * @return  Factory<Order>
     */
    public function refunded(): Factory
    {
        return $this->state(fn () => ['post_status' => 'wc-refunded']);
    }

    /**
     * Applies withMeta state to model.
     *
     * @return  Factory<Order>
     */
    public function withMeta(): Factory
    {
        return $this->afterCreating(function (Order $order) {
            $order->createMeta([
                '_order_currency'     => $this->faker->currencyCode,
                '_order_total'        => $this->faker->randomFloat(2, 0, 200),
                '_order_tax'          => $this->faker->randomFloat(2, 0, 200),
                '_date_completed'     => $this->faker->dateTime->format('Y-m-d H:i:s'),
                '_billing_first_name' => $this->faker->firstName,
                '_billing_last_name'  => $this->faker->lastName,
                '_billing_company'    => $this->faker->optional()->company,
                '_billing_address_1'  => $this->faker->streetAddress,
                '_billing_address_2'  => $this->faker->optional()->secondaryAddress,
                '_billing_city'       => $this->faker->city,
                '_billing_state'      => $this->faker->state,
                '_billing_postcode'   => $this->faker->postcode,
                '_billing_country'    => $this->faker->country,
                '_billing_email'      => $this->faker->email,
                '_billing_phone'      => $this->faker->phoneNumber,
            ]);
        });
    }

    /**
     * Applies withShipping state to model.
     *
     * @return  Factory<Order>
     */
    public function withShipping(): Factory
    {
        return $this->afterCreating(function (Order $order) {
            $order->createMeta([
                '_order_shipping'      => $this->faker->randomFloat(2, 0, 200),
                '_order_shipping_tax'  => $this->faker->randomFloat(2, 0, 200),
                '_shipping_first_name' => $this->faker->firstName,
                '_shipping_last_name'  => $this->faker->lastName,
                '_shipping_company'    => $this->faker->optional()->company,
                '_shipping_address_1'  => $this->faker->streetAddress,
                '_shipping_address_2'  => $this->faker->optional()->secondaryAddress,
                '_shipping_city'       => $this->faker->city,
                '_shipping_state'      => $this->faker->state,
                '_shipping_postcode'   => $this->faker->postcode,
                '_shipping_country'    => $this->faker->country,
            ]);
        });
    }

    /**
     * Applies paid state to model.
     *
     * @return  Factory<Order>
     */
    public function paid(): Factory
    {
        return $this->afterCreating(function (Order $order) {
            $paymentMethod = $this->faker->word;

            $order->createMeta([
                '_date_paid'            => $this->faker->dateTime->format('Y-m-d H:i:s'),
                '_payment_method'       => $paymentMethod,
                '_payment_method_title' => ucfirst($paymentMethod),
                '_transaction_id'       => $this->faker->asciify('*******'),
            ]);
        });
    }
}
