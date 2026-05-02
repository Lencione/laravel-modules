<?php

namespace Lencione\LaravelModules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MakeModule extends Command
{
    protected $signature = 'make:module {module} {folder?}';

    protected $description = 'Cria uma estrutura de pastas para um módulo Laravel.';

    public function handle(): int
    {
        $module = $this->argument('module');
        $folder = $this->argument('folder');
        $modulePath = $this->modulePath($module);

        if ($folder) {
            return $this->createSingleFolder($modulePath, $folder);
        }

        return $this->createFullModule($module, $modulePath);
    }

    private function createSingleFolder(string $modulePath, string $folder): int
    {
        if (! in_array($folder, $this->moduleFolders(), true)) {
            $this->error("Pasta inválida: {$folder}. Opções: " . implode(', ', $this->moduleFolders()));

            return CommandAlias::FAILURE;
        }

        $folderPath = "{$modulePath}/{$folder}";

        if (is_dir($folderPath)) {
            $this->warn("A pasta {$folder} já existe.");

            return CommandAlias::SUCCESS;
        }

        File::ensureDirectoryExists($folderPath, 0755);
        $this->info("Pasta {$folder} criada com sucesso.");

        return CommandAlias::SUCCESS;
    }

    private function createFullModule(string $module, string $modulePath): int
    {
        foreach ($this->moduleFolders() as $folder) {
            $folderPath = "{$modulePath}/{$folder}";

            if (is_dir($folderPath)) {
                $this->warn("A pasta {$folder} já existe.");
                continue;
            }

            File::ensureDirectoryExists($folderPath, 0755);
        }

        $this->info("Módulo {$module} criado com sucesso.");

        $this->generateFiles($module);

        return CommandAlias::SUCCESS;
    }

    private function generateFiles(string $module): void
    {
        $this->call('module:controller', ['module' => $module]);
        $this->call('module:request', ['module' => $module, 'target' => 'Store' . $module . 'Request']);
        $this->call('module:request', ['module' => $module, 'target' => 'Update' . $module . 'Request']);
        $this->call('module:resource', ['module' => $module]);
        $this->call('module:model', ['module' => $module]);
        $this->call('module:service', ['module' => $module]);
        $this->call('module:route', ['module' => $module]);
    }

    private function modulePath(string $module): string
    {
        $base = config('modules.path') ?? app_path('Modules');

        return rtrim($base, '/') . "/{$module}";
    }

    /**
     * @return array<int, string>
     */
    private function moduleFolders(): array
    {
        return config('modules.folders', [
            'Actions',
            'Controllers',
            'Models',
            'Requests',
            'Resources',
            'Rules',
            'Events',
            'Listeners',
            'Jobs',
            'Routes',
            'Services',
            'Views',
        ]);
    }
}
