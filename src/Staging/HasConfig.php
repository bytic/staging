<?php

namespace Nip\Staging\Staging;

use Nip\Config\Config;

/**
 * Trait HasConfig
 * @package Nip\Staging\Staging
 */
trait HasConfig
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (!$this->config) {
            $this->config = $this->initConfig();
        }

        return $this->config;
    }

    /**
     * @return Config
     */
    public function initConfig()
    {
        $config = \config();

        return $config;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function hasConfigFile($file)
    {
        return is_file($this->getConfigFolder() . $file);
    }

    /**
     * @return null
     */
    protected function getConfigFolder()
    {
        return defined('CONFIG_PATH') ? CONFIG_PATH : null;
    }
}