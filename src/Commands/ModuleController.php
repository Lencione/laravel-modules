<?php

namespace Lencione\LaravelModules\Commands;

class ModuleController extends AbstractMakeModuleCommand
{
    protected $signature = 'module:controller {module} {target?}';

    protected $description = 'Cria um controller para um módulo específico.';

    protected string $folder = 'Controllers';

    protected string $stub = 'module-controller.stub';

    protected ?string $defaultSuffix = 'Controller';
}
