<?php

namespace Lencione\LaravelModules\Commands;

use Illuminate\Console\Command;
use Lencione\LaravelModules\Helpers\Create;

class ModuleRoute extends Command
{
    protected $signature = 'module:route {module}';

    protected $description = 'Cria os arquivos de rota (web/api) para um módulo específico.';

    public function handle(): int
    {
        $module = $this->argument('module');

        Create::moduleFromStub('Routes', 'module-route.stub', $module, 'web');
        Create::moduleFromStub('Routes', 'module-route.stub', $module, 'api');

        $this->info('Arquivos de rotas criados com sucesso.');

        return self::SUCCESS;
    }
}
