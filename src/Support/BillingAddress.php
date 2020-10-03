<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Support;

use Corcel\Model;

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
 * @property string|null  $email
 * @property string|null  $phone
 */
class BillingAddress extends Address
{
    /**
     * @inheritDoc
     *
     * @param \Corcel\Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model, 'billing');
    }
}
