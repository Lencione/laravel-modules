<?php

namespace Lencione\LaravelModules\Commands;

class ModuleService extends AbstractMakeModuleCommand
{
    protected $signature = 'module:service {module} {target?}';

    protected $description = 'Cria um service para um módulo específico.';

    protected string $folder = 'Services';

    protected string $stub = 'module-service.stub';

    protected ?string $defaultSuffix = 'Service';
}
