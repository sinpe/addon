<?php

namespace Sinpe\Addon\Console\Command;

use Anomaly\Streams\Platform\Support\Parser;
use Illuminate\Filesystem\Filesystem;

/**
 * Class WriteAddonStreamLang.
 */
class WriteAddonStreamLang
{
    /**
     * The addon path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new WriteAddonStreamLang instance.
     *
     * @param $path
     * @param $type
     * @param $slug
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
    public function handle(Parser $parser, Filesystem $filesystem)
    {
        $path = "{$this->path}/resources/lang/en/stream.php";

        $template = $filesystem->get(
            $this->bridge->getBasePath('vendor/anomaly/streams-platform/resources/stubs/addons/resources/lang/en/stream.stub')
        );

        $filesystem->makeDirectory(dirname($path), 0755, true, true);

        $filesystem->put($path, $parser->parse($template));
    }
}
