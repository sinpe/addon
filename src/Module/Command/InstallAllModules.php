<?php

namespace Sinpe\Addon\Module\Command;

/**
 * Class InstallAllModules.
 */
class InstallAllModules
{
    /**
     * The seed flag.
     *
     * @var bool
     */
    protected $seed;

    /**
     * Create a new InstallAllModules instance.
     *
     * @param bool $seed
     */
    public function __construct($seed = false)
    {
        $this->seed = $seed;
    }

    /**
     * Get the seed flag.
     *
     * @return bool
     */
    public function getSeed()
    {
        return $this->seed;
    }
}
