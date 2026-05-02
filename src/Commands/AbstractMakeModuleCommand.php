<?php

namespace Lencione\LaravelModules\Commands;

use Illuminate\Console\Command;
use Lencione\LaravelModules\Helpers\Create;

abstract class AbstractMakeModuleCommand extends Command
{
    protected string $folder;

    protected string $stub;

    protected ?string $defaultSuffix = null;

    public function handle(): int
    {
        $module = $this->argument('module');
        $target = $this->argument('target');

        if (! $target) {
            if ($this->defaultSuffix === null) {
                $this->error('O argumento "target" é obrigatório.');

                return self::FAILURE;
            }

            $target = $module . $this->defaultSuffix;
        }

        if (Create::moduleFromStub($this->folder, $this->stub, $module, $target)) {
            $this->info("{$target} criado com sucesso.");

            return self::SUCCESS;
        }

        $this->error('Erro ao criar. O arquivo já existe ou o módulo não foi inicializado (rode make:module antes).');

        return self::FAILURE;
    }
}
