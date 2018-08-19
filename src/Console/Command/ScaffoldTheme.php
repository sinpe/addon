<?php

namespace Sinpe\Addon\Console\Command;

use Anomaly\Streams\Platform\Support\Parser;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ScaffoldTheme.
 */
class ScaffoldTheme
{
    /**
     * Copy these theme folders.
     *
     * @var array
     */
    protected $copy = [
        'fonts',
        'js',
        'scss',
        'views',
    ];

    /**
     * The addon path.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new ScaffoldTheme instance.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Handle the command.
     *
     * @param Parser     $parser
     * @param Filesystem $filesystem
     */
    public function handle(Filesystem $filesystem)
    {
        foreach ($this->copy as $copy) {
            $filesystem->copyDirectory(
                $this->bridge->getBasePath('vendor/anomaly/streams-platform/resources/stubs/addons/resources/'.$copy),
                "{$this->path}/resources/".$copy
            );
        }
    }
}
