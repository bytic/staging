<?php

namespace Nip\Staging;

use Nip\Staging\Stage\Stage;

/**
 * Class Staging
 * @package Nip\Staging
 */
class Staging
{
    use Staging\HasCurrentStage;
    use Staging\HasConfig;

    /**
     * @var Stage[]
     */
    protected $stages = null;

    protected $publicStages = ['production', 'staging', 'demo'];

    protected $testingStages = ['local'];

    /**
     * @return Stage
     */
    public function getStage()
    {
        if (!$this->stage) {
            $stage = strtolower($this->determineStage());
            $this->updateStage($stage);
        }

        return $this->stage;
    }

    /**
     * @return string
     */
    public function determineStage()
    {
        $stage = $this->determineStageFromConf();
        if ($stage) {
            return $stage;
        }

        $stage = $this->determineStageFromHOST();
        if ($stage) {
            return $stage;
        }

        return 'production';
    }

    /**
     * @return bool
     */
    public function determineStageFromConf()
    {
        if ($this->getConfig()->has('app.env')) {
            return $this->getConfig()->get('app.env');
        }

        if ($this->getConfig()->has('staging.current')) {
            return $this->getConfig()->get('staging.current');
        }

        return false;
    }

    /**
     * @return bool|int|string
     */
    public function determineStageFromHOST()
    {
        $returnStage = false;
        if (isset($_SERVER['SERVER_NAME'])) {
            foreach ($this->getStages() as $stage => $hosts) {
                foreach ($hosts as $host) {
                    if ($this->matchHost($host, $_SERVER['SERVER_NAME'])) {
                        $returnStage = $stage;
                        break 2;
                    }
                }
            }
        }

        return $returnStage;
    }

    /**
     * @return Stage[]
     */
    public function getStages()
    {
        if ($this->stages == null) {
            $this->initStages();
        }

        return $this->stages;
    }

    protected function initStages()
    {
        $this->stages = $this->generateStages();
    }

    /**
     * @return array
     */
    protected function generateStages()
    {
        if ($this->getConfig()->has('HOSTS') == false) {
            return [];
        }

        $stageObj = $this->getConfig()->get('HOSTS');
        $stages = $stageObj->toArray();
        if (!is_array($stages)) {
            return [];
        }

        foreach ($stages as &$stage) {
            if (strpos($stage, ',')) {
                $stage = array_map("trim", explode(',', $stage));
            } else {
                $stage = [trim($stage)];
            }
        }
        return $stages;
    }

    /**
     * @param $key
     * @param $host
     * @return int
     */
    public function matchHost($key, $host)
    {
        return preg_match('/^' . strtr($key, ['*' => '.*', '?' => '.?']) . '$/i', $host);
    }

    /**
     * @param $name
     * @return bool
     */
    public function isInPublicStages($name)
    {
        return in_array($name, $this->publicStages);
    }

    /**
     * @param $name
     * @return bool
     */
    public function isInTestingStages($name)
    {
        return in_array($name, $this->testingStages);
    }
}
