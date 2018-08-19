<?php

namespace Sinpe\Addon\Extension\Command;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

/**
 * Class InstallExtensionsTableHandler.
 */
class InstallExtensionsTableHandler
{
    /**
     * The schema builder object.
     *
     * @var Builder
     */
    protected $schema;

    /**
     * Create a new InstallExtensionsTableHandler instance.
     */
    public function __construct()
    {
        $this->schema = app('db')->connection()->getSchemaBuilder();
    }

    /**
     * Install the extensions table.
     */
    public function handle()
    {
        $this->schema->dropIfExists('addons_extensions');

        $this->schema->create(
            'addons_extensions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->boolean('installed')->default(0);
                $table->boolean('enabled')->default(0);
            }
        );
    }
}
