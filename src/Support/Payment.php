<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\WooCommerce\Model\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;

class Payment implements Arrayable, Jsonable
{
    /**
     * The payment method.
     *
     * @var  string|null
     */
    public $method;

    /**
     * The payment method title.
     *
     * @var  string|null
     */
    public $method_title;

    /**
     * The payment transation identificator.
     *
     * @var  string|null
     */
    public $transaction_id;

    /**
     * The payment constructor.
     *
     * @param  \Corcel\WooCommerce\Model\Order  $order
     */
    public function __construct(Order $order)
    {
        $this->method         = $order->getMeta('_payment_method');
        $this->method_title   = $order->getMeta('_payment_method_title');
        $this->transaction_id = $order->getMeta('_transaction_id');
    }

    /**
     * @inheritDoc
     *
     * @return  mixed[]
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
