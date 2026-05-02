<?php

namespace Lencione\LaravelModules\Commands;

class ModuleRules extends AbstractMakeModuleCommand
{
    protected $signature = 'module:rule {module} {target}';

    protected $description = 'Cria uma rule para um módulo específico.';

    protected string $folder = 'Rules';

    protected string $stub = 'module-rule.stub';
}
