<?php

namespace Corcel\WooCommerce\Model\Builder;

use Corcel\Model\Builder\PostBuilder;

class OrderBuilder extends PostBuilder
{
    /**
     * @return $this
     */
    public function cancelled()
    {
        return $this->status('cancelled');
    }

    /**
     * @return $this
     */
    public function completed()
    {
        return $this->status('completed');
    }

    /**
     * @return $this
     */
    public function failed()
    {
        return $this->status('failed');
    }

    /**
     * @return $this
     */
    public function onHold()
    {
        return $this->status('on-hold');
    }

    /**
     * @return $this
     */
    public function pending()
    {
        return $this->status('pending');
    }

    /**
     * @return $this
     */
    public function processing()
    {
        return $this->status('processing');
    }

    /**
     * @return $this
     */
    public function refunded()
    {
        return $this->status('refunded');
    }

    /**
     * @param $this
     */
    public function status($status)
    {
        $status = 'wc-' === substr($status, 0, 3) ? substr($status, 3) : $status;

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
            $status = 'wc-' . $status;
        }

        return parent::status($status);
    }
}
