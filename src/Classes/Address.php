<?php

namespace Corcel\WooCommerce;

use Corcel\Model\Collection\MetaCollection;

class Address
{
    /**
     * @param $class
     * @param MetaCollection $meta
     * @param $type
     */
    public function __construct($class, MetaCollection $meta, $type = 'billing')
    {
        $class = class_basename($class);

        foreach ($this->getKeys($type) as $key) {
            $meta_key = $this->getMetaKey($class, $type, $key);

            $this->items[$key] = $meta->$meta_key;
        }
    }

    /**
     * @param $type
     * @return mixed
     */
    protected function getKeys($type)
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

        if ('billing' === $type) {
            $keys = array_merge($keys, [
                'email',
                'phone',
            ]);
        }

        return $keys;
    }

    /**
     * @param $class
     * @param $type
     * @param $key
     */
    protected function getMetaKey($class, $type, $key)
    {
        switch ($class) {
            case 'Customer':
                $pattern = '%s_%s';
                break;
            case 'Order':
            default:
                $pattern = '_%s_%s';
        }

        return sprintf($pattern, $type, $key);
    }
}
