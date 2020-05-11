<?php

namespace Nip\Staging\Staging;

use Nip\Staging\Stage\Stage;
use Nip\Staging\Staging;

/**
 * Trait HasCurrentStage
 * @package Nip\Staging\Staging
 */
trait HasCurrentStage
{


    /**
     * @var Stage
     */
    protected $stage;

    /**
     * @param $name
     * @return $this
     */
    public function updateStage($name)
    {
        $this->stage = $this->newStage($name);

        return $this;
    }

    /**
     * @param $name
     * @return Stage
     */
    public function newStage($name)
    {
        $stage = new Stage();
        $stage->setManager($this);
        $stage->setName($name);

        return $stage;
    }
}