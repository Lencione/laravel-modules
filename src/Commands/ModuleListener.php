<?php

namespace Lencione\LaravelModules\Commands;

class ModuleListener extends AbstractMakeModuleCommand
{
    protected $signature = 'module:listener {module} {target}';

    protected $description = 'Cria um listener para um módulo específico.';

    protected string $folder = 'Listeners';

    protected string $stub = 'module-listener.stub';
}
