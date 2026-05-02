<?php

namespace Lencione\LaravelModules\Commands;

class ModuleModel extends AbstractMakeModuleCommand
{
    protected $signature = 'module:model {module} {target?}';

    protected $description = 'Cria um model para um módulo específico.';

    protected string $folder = 'Models';

    protected string $stub = 'module-model.stub';

    protected ?string $defaultSuffix = '';
}
