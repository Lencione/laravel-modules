<?php

namespace Lencione\LaravelModules\Commands;

class ModuleAction extends AbstractMakeModuleCommand
{
    protected $signature = 'module:action {module} {target}';

    protected $description = 'Cria uma action para um módulo específico.';

    protected string $folder = 'Actions';

    protected string $stub = 'module-action.stub';
}
