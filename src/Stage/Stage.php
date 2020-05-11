<?php

namespace Nip\Staging\Stage;

use Nip\Request;
use Nip\Staging\Staging;

/**
 * Class Stage
 * @package Nip\Staging\Stage
 */
class Stage
{
    use Traits\HasConfigTrait;
    use Traits\HasHosts;
    use Traits\SchemeTrait;

    protected $manager;

    protected $name;

    protected $type = null;

    protected $host;

    protected $baseURL;

    protected $projectDIR;

    public function init()
    {
        $this->initHostsFromConfig();
    }

    /**
     * @param $hosts
     * @return $this
     */
    public function setHosts($hosts)
    {
        $this->hosts = $hosts;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        foreach ($this->hosts as $host) {
            if (preg_match('/^' . strtr($host, ['*' => '.*', '?' => '.?']) . '$/i', $_SERVER['SERVER_NAME'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBaseURL()
    {
        if (!$this->baseURL) {
            $this->baseURL = $this->getHTTP() . $this->getHost() . $this->getProjectDir();
        }

        return $this->baseURL;
    }

    /**
     * @return string
     */
    public function getHTTP()
    {
        $https = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
            $https = true;
        }

        return "http" . ($https ? "s" : "") . "://";
    }

    /**
     * @return mixed|string
     */
    public function getHost()
    {
        if (!$this->host) {
            if ($this->getConfig()->has('HOST.automatic') && $this->getConfig()->get('HOST.automatic') === false) {
                $this->host = reset($this->hosts);
            }

            if (!$this->host) {
                $this->host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']
                    : 'localhost';
            }
        }

        return $this->host;
    }

    /**
     * @return string
     */
    public function getProjectDir()
    {
        if (!$this->projectDIR) {
            $this->projectDIR = $this->initProjectDir();
        }

        return $this->projectDIR;
    }

    /**
     * @param $dir
     */
    public function setProjectDir($dir)
    {
        $this->projectDIR = $dir;
    }

    /**
     * @return string
     */
    public function initProjectDir()
    {
        $request = new Request();

        return $request->path();
    }

    /**
     * @return bool
     */
    public function inProduction()
    {
        return $this->name == 'production';
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return !$this->isAuthorized() && $this->getManager()->isInPublicStages($this->getType());
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return isset($_COOKIE['authorized']) && $_COOKIE['authorized'] === 'true';
    }

    /**
     * @return Staging
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param Staging $manager
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return null
     */
    public function getType()
    {
        if ($this->type === null) {
            $this->initType();
        }

        return $this->type;
    }

    public function initType()
    {
        $config = $this->getConfig();
        if (isset($config->STAGE) && isset($config->STAGE->type)) {
            $this->type = $config->STAGE->type;
        } else {
            $this->type = $this->name;
        }
    }

    /**
     * @return bool
     */
    public function inTesting()
    {
        return $this->isAuthorized() || $this->getManager()->isInTestingStages($this->getType());
    }

    public function doAuthorize()
    {
        setcookie('authorized', 'true', time() + 60 * 60 * 24, '/');
    }
}
