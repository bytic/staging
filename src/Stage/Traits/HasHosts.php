<?php

namespace Nip\Staging\Stage\Traits;

/**
 * Trait HasHosts
 * @package Nip\Staging\Stage\Traits
 */
trait HasHosts
{
    protected $hosts = [];

    protected function initHostsFromConfig()
    {
        if ($this->getConfig()->has('HOST.url') === false) {
            return;
        }
        $hosts = $this->getConfig()->get('HOST.url');

        if (strpos($hosts, ',')) {
            $hosts = array_map('trim', explode(',', $hosts));
        } else {
            $hosts = [trim($hosts)];
        }
        $this->setHosts($hosts);
    }

    /**
     * @param $hosts
     *
     * @return $this
     */
    public function setHosts(array $hosts)
    {
        $this->hosts = $hosts;

        return $this;
    }
}
