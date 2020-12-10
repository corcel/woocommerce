<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\Model;
use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;

class Address implements Arrayable, Jsonable
{
    /**
     * The model instance.
     *
     * @var  \Corcel\Model
     */
    protected $model;

    /**
     * The address type.
     *
     * @var  string
     */
    protected $type;

    /**
     * The address attributes.
     *
     * @var  mixed[]
     */
    protected $attributes = [];

    /**
     * The address constructor.
     *
     * @param  \Corcel\Model  $model
     * @param  string         $type
     */
    public function __construct(Model $model, string $type)
    {
        $this->model = $model;
        $this->type  = $type;

        $this->parseAttributes();
    }

    /**
     * Parse address attributes.
     *
     * @return  void
     */
    protected function parseAttributes(): void
    {
        foreach ($this->attributeKeys() as $key) {
            $metaKey = $this->getMetaKey($key);

            // @phpstan-ignore-next-line
            $this->attributes[$key] = $this->model->getMeta($metaKey);
        }
    }

    /**
     * List of the attribute keys.
     *
     * @return  string[]
     */
    protected function attributeKeys(): array
    {
        $keys = [
            'first_name',
            'last_name',
            'company',
            'address_1',
            'address_2',
            'city',
            'state',
            'postcode',
            'country',
        ];

        if ($this->type === 'billing') {
            $keys = array_merge($keys, [
                'email',
                'phone',
            ]);
        }

        return $keys;
    }

    /**
     * Get meta key for given attribute name.
     *
     * @param   string  $key
     * @return  string
     */
    protected function getMetaKey(string $key): string
    {
        $pattern = $this->metaKeyPattern();

        return sprintf($pattern, $this->type, $key);
    }

    /**
     * Get meta key pattern based on model.
     *
     * @return  string
     */
    protected function metaKeyPattern(): string
    {
        if ($this->model instanceof Customer) {
            return '%s_%s';
        } elseif ($this->model instanceof Order) {
            return '_%s_%s';
        }

        throw new InvalidArgumentException(sprintf(
            'Model "%s" cannot have address.',
            get_class($this->model)
        ));
    }

    /**
     * @inheritDoc
     *
     * @return  mixed[]
     */
    public function toArray(): array
    {
        return $this->attributes;
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
            throw new InvalidArgumentException('An error occured while converting order address to JSON.');
        }

        return $json;
    }

    /**
     * Magic method to get address attributes.
     *
     * @param   string  $key
     * @return  mixed
     */
    public function __get(string $key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        throw new InvalidArgumentException("Property {$key} does not exists.");
    }
}
