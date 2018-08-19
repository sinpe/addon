<?php 
namespace Sinpe\Addon\Command;

use Sinpe\Addon\Addon;
use Sinpe\Addon\Collection;

class GetAddon
{

    /**
     * The addon identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Create a new GetAddon instance.
     *
     * @param string $identifier The addon namespace / slug
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Handle the command.
     *
     * @param Collection $addons
     *
     * @return Addon
     */
    public function handle(Collection $addons)
    {
        return $addons->get($this->identifier);
    }
}
