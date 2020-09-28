<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\Model;
use Corcel\Model\Collection\MetaCollection;
use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use InvalidArgumentException;

class Address
{
    /**
     * The model instance.
     *
     * @var  \Corcel\Model
     */
    protected $model;

    /**
     * The model meta colection.
     *
     * @var  \Corcel\Model\Collection\MetaCollection<string>
     */
    protected $meta;

    /**
     * The address type.
     *
     * @var  string
     */
    protected $type;

    /**
     * The address attributes.
     *
     * @var  string[]
     */
    protected $attributes = [];

    /**
     * The address constructor.
     *
     * @param  \Corcel\Model                                    $model
     * @param  \Corcel\Model\Collection\MetaCollection<string>  $meta
     * @param  string                                           $type
     */
    public function __construct(Model $model, MetaCollection $meta, string $type)
    {
        $this->model = $model;
        $this->meta  = $meta;
        $this->type  = $type;

        $this->parseAttributes();
    }

    protected function parseAttributes(): void
    {
        foreach ($this->attributeKeys() as $key) {
            $metaKey = $this->getMetaKey($key);

            $this->attributes[$key] = $this->meta->{$metaKey};
        }
    }

    /**
     * The the attribute keys.
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

    protected function getMetaKey(string $key): string
    {
        $pattern = $this->metaKeyPattern();

        return sprintf($pattern, $this->type, $key);
    }

    protected function metaKeyPattern(): string
    {
        $class = get_class($this->model);

        if ($class === Customer::class) {
            return '%s_%s';
        } elseif ($class === Order::class) {
            return '_%s_%s';
        }

        return '';
    }

    public function __get(string $key): string
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        throw new InvalidArgumentException("Property {$key} does not exists.");
    }
}
