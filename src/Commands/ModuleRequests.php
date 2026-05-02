<?php

namespace Lencione\LaravelModules\Commands;

class ModuleRequests extends AbstractMakeModuleCommand
{
    protected $signature = 'module:request {module} {target}';

    protected $description = 'Cria um request para um módulo específico.';

    protected string $folder = 'Requests';

    protected string $stub = 'module-request.stub';
}
