<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\WooCommerce\Model\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;

/**
 * @implements Arrayable<string, mixed>
 */
class Payment implements Arrayable, Jsonable
{
    /**
     * The payment method.
     *
     * @var  string|null
     */
    public ?string $method = null;

    /**
     * The payment method title.
     *
     * @var  string|null
     */
    public ?string $method_title = null;

    /**
     * The payment transation identificator.
     *
     * @var  string|null
     */
    public ?string $transaction_id = null;

    /**
     * The payment constructor.
     *
     * @param  Order  $order
     */
    public function __construct(Order $order)
    {
        $method = $order->getMeta('_payment_method');
        $methodTitle   = $order->getMeta('_payment_method_title');
        $transactionId = $order->getMeta('_transaction_id');

        $this->method         = is_scalar($method) ? (string) $method : null;
        $this->method_title   = is_scalar($methodTitle) ? (string) $methodTitle : null;
        $this->transaction_id = is_scalar($transactionId) ? (string) $transactionId : null;
    }

    /**
     * @inheritDoc
     *
     * @return  array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'method'         => $this->method,
            'method_title'   => $this->method_title,
            'transaction_id' => $this->transaction_id,
        ];
    }

    /**
     * @inheritDoc
     *
     * @param   int     $options
     * @return  string
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if ($json === false || JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('An error occured while converting order payment to JSON.');
        }

        return $json;
    }
}
