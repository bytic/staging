<?php

namespace Nip\Staging\Stage\Traits;

use Nip\Config\Config;

/**
 * Trait HasConfigTrait
 * @package Nip\Staging\Stage\Traits
 */
trait HasConfigTrait
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
            $this->initConfig();
        }

        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function initConfig()
    {
        $config = $this->newConfig();
        if ($this->hasConfigFile()) {
            $config->mergeFile($this->getConfigPath());
        }
        $this->setConfig($config);
    }

    /**
     * @return Config
     */
    public function newConfig()
    {
        return new Config();
    }

    /**
     * @return bool
     */
    protected function hasConfigFile()
    {
        return is_file($this->getConfigPath());
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return $this->getConfigFolder().$this->name.'.ini';
    }

    /**
     * @return null
     */
    protected function getConfigFolder()
    {
        return defined('CONFIG_STAGING_PATH') ? CONFIG_STAGING_PATH : null;
    }
}
