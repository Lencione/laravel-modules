<?php

namespace Lencione\LaravelModules\Commands;

class ModuleJob extends AbstractMakeModuleCommand
{
    protected $signature = 'module:job {module} {target}';

    protected $description = 'Cria um job para um módulo específico.';

    protected string $folder = 'Jobs';

    protected string $stub = 'module-job.stub';
}
