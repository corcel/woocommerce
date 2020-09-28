<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\Model;
use Corcel\Model\Collection\MetaCollection;

/**
 * @property string|null  $first_name
 * @property string|null  $last_name
 * @property string|null  $company
 * @property string|null  $address_1
 * @property string|null  $address_2
 * @property string|null  $city
 * @property string|null  $state
 * @property string|null  $postcode
 * @property string|null  $country
 */
class ShippingAddress extends Address
{
    /**
     * @inheritDoc
     */
    public function __construct(Model $model, MetaCollection $meta)
    {
        parent::__construct($model, $meta, 'shipping');
    }
}
