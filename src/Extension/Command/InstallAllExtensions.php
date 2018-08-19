<?php

namespace Sinpe\Addon\Extension\Command;

/**
 * Class InstallAllExtensions.
 */
class InstallAllExtensions
{
    /**
     * The seed flag.
     *
     * @var bool
     */
    protected $seed;

    /**
     * Create a new InstallAllExtensions instance.
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
