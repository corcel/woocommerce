<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Model\Builder;

use Corcel\Model\Builder\PostBuilder;

class OrderBuilder extends PostBuilder
{
    /**
     * Scope a query to only cancelled orders.
     */
    public function cancelled(): PostBuilder
    {
        return $this->status('cancelled');
    }

    /**
     * Scope a query to only completed orders.
     */
    public function completed(): PostBuilder
    {
        return $this->status('completed');
    }

    /**
     * Scope a query to only failed orders.
     */
    public function failed(): PostBuilder
    {
        return $this->status('failed');
    }

    /**
     * Scope a query to only on hold orders.
     */
    public function onHold(): PostBuilder
    {
        return $this->status('on-hold');
    }

    /**
     * Scope a query to only pending orders.
     */
    public function pending(): PostBuilder
    {
        return $this->status('pending');
    }

    /**
     * Scope a query to only processing orders.
     */
    public function processing(): PostBuilder
    {
        return $this->status('processing');
    }

    /**
     * Scope a query to only refunded orders.
     */
    public function refunded(): PostBuilder
    {
        return $this->status('refunded');
    }

    /**
     * Scope a query to orders with given status.
     */
    public function status($status): PostBuilder
    {
        $status = substr($status, 0, 3) === 'wc-' ? substr($status, 3) : $status;

        $builtin = [
            'cancelled',
            'completed',
            'failed',
            'on-hold',
            'pending',
            'processing',
            'refunded',
        ];

        if (in_array($status, $builtin)) {
            $status = 'wc-'.$status;
        }

        return parent::status($status);
    }
}
