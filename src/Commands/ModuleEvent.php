<?php

namespace Lencione\LaravelModules\Commands;

class ModuleEvent extends AbstractMakeModuleCommand
{
    protected $signature = 'module:event {module} {target}';

    protected $description = 'Cria um event para um módulo específico.';

    protected string $folder = 'Events';

    protected string $stub = 'module-event.stub';
}
