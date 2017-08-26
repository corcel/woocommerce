<?php

namespace Corcel\WooCommerce\Traits;

trait StatusesTrait
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeCancelled($query)
    {
        return $query->where('post_status', 'cancelled');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->where('post_status', 'completed');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFailed($query)
    {
        return $query->where('post_status', 'failed');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOnHold($query)
    {
        return $query->where('post_status', 'on-hold');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePending($query)
    {
        return $query->where('post_status', 'pending');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeProcessing($query)
    {
        return $query->where('post_status', 'processing');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeRefunded($query)
    {
        return $query->where('post_status', 'refunded');
    }
}
