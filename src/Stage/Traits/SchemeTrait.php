<?php

namespace Nip\Staging\Stage\Traits;

/**
 * Trait SchemeTrait
 * @package Nip\Staging\Stage\Traits
 */
trait SchemeTrait
{
    /**
     * @var null|bool
     */
    protected $isSecure = null;

    /**
     * @var null|bool
     */
    protected $scheme = null;

    /**
     * @return string
     */
    public function getHTTP()
    {
        return $this->getScheme() . '://';
    }

    /**
     * @return string|null
     */
    public function getScheme()
    {
        if ($this->scheme == null) {
            $this->scheme = $this->generateScheme();
        }
        return $this->scheme;
    }

    /**
     * @return string
     */
    protected function generateScheme()
    {
        $https = $this->isSecure();
        return 'http' . ($https ? 's' : '');
    }

    /**
     * @return void|null
     */
    public function isSecure()
    {
        if ($this->isSecure == null) {
            $this->isSecure = $this->generateIsSecure();
        }
        return $this->isSecure;
    }

    /**
     * @return bool
     */
    protected function generateIsSecure()
    {
        $https = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $https = true;
        }
        return $https;
    }
}
