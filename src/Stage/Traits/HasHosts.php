<?php

namespace Nip\Staging\Stage\Traits;

/**
 * Trait HasHosts
 * @package Nip\Staging\Stage\Traits
 */
trait HasHosts
{
    protected $hosts = null;

    /**
     * @param $hosts
     *
     * @return $this
     */
    public function getHosts()
    {
        if ($this->hosts === null) {
            $this->initHosts();
        }

        return $this->hosts;
    }

    protected function initHosts()
    {
        $this->setHosts($this->generateHosts());
    }

    /**
     * @return array
     */
    protected function generateHosts()
    {
        if ($this->getConfig()->has('HOST.url')) {
            $hosts = $this->getConfig()->get('HOST.url');

            if (strpos($hosts, ',')) {
                $hosts = array_map('trim', explode(',', $hosts));
            } else {
                $hosts = [trim($hosts)];
            }
            return $hosts;
        }

        $stages = $this->getManager()->getStages();
        if (isset($stages[$this->getName()]) && is_array($stages[$this->getName()])) {
            return $stages[$this->getName()];
        }
        return [];
    }

    /**
     * @param array $hosts
     */
    public function setHosts(array $hosts)
    {
        $this->hosts = $hosts;
    }
}
