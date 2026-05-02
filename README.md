# Laravel Modules

Gerador de módulos para projetos Laravel com estrutura padronizada.

Cria a árvore `app/Modules/{Modulo}/` com pastas convencionadas e arquivos base
a partir de stubs. Carrega automaticamente os arquivos de rotas (`web.php` e
`api.php`) de cada módulo.

## Requisitos

- PHP `^8.3 | ^8.4`
- Laravel `^11 | ^12 | ^13`

## Instalação

```bash
composer require lencione/laravel-modules
```

O `LaravelModulesServiceProvider` é registrado automaticamente via package
discovery do Laravel. Não há nada para registrar manualmente.

## O que o package faz

### 1. Comandos artisan

| Comando | Descrição |
|---|---|
| `make:module {nome} {pasta?}` | Cria a estrutura completa do módulo (ou apenas uma pasta). Quando completa, também gera controller, requests Store/Update, resource, model, service e arquivos de rota. |
| `module:action {modulo} {target}` | Cria uma action. |
| `module:controller {modulo} {target?}` | Cria um controller. Sem `target`, usa `{modulo}Controller`. |
| `module:event {modulo} {target}` | Cria um event. |
| `module:job {modulo} {target}` | Cria um job. |
| `module:listener {modulo} {target}` | Cria um listener. |
| `module:model {modulo} {target?}` | Cria um model. Sem `target`, usa `{modulo}`. |
| `module:request {modulo} {target}` | Cria um FormRequest. |
| `module:resource {modulo} {target?}` | Cria um JsonResource. Sem `target`, usa `{modulo}Resource`. |
| `module:route {modulo}` | Cria os arquivos `web.php` e `api.php` do módulo. |
| `module:rule {modulo} {target}` | Cria uma ValidationRule. |
| `module:service {modulo} {target?}` | Cria um service. Sem `target`, usa `{modulo}Service`. |

Exemplos:

```bash
php artisan make:module Users
# Gera app/Modules/Users/{Actions,Controllers,Models,...} +
# UsersController, StoreUsersRequest, UpdateUsersRequest, UsersResource,
# Users (model), UsersService, web.php, api.php

php artisan module:action Users SendWelcomeEmail
# Gera app/Modules/Users/Actions/SendWelcomeEmail.php

php artisan module:request Users StoreUserRequest
# Gera app/Modules/Users/Requests/StoreUserRequest.php
```

Subdiretórios funcionam usando `/` no `target`:

```bash
php artisan module:service Users Auth/LoginService
# Gera app/Modules/Users/Services/Auth/LoginService.php
# Namespace: App\Modules\Users\Services\Auth
```

### 2. Auto-load de rotas

Para cada `app/Modules/{Modulo}/Routes/web.php` e `api.php` encontrado, o
package registra automaticamente as rotas:

- `web.php` → middleware `web`, sem prefixo
- `api.php` → middleware `api`, prefixo `api/`

### 3. `BaseService`

Service genérico com CRUD básico em `Lencione\LaravelModules\Services\BaseService`.

```php
namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Lencione\LaravelModules\Services\BaseService;

class UsersService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new User);
    }
}
```

Métodos disponíveis:

| Método | Descrição |
|---|---|
| `getAll(?int $perPage = null)` | Retorna paginado, ordenado por `id`. Aceita `perPage` opcional. |
| `getAllWithoutPagination()` | Retorna `Collection` completa, ordenada por `id`. |
| `getById(int\|string $id)` | Retorna o item ou lança `ModelNotFoundException` (404 automático no Laravel). |
| `store(array $validated)` | Cria via `model->create()`. |
| `update(int\|string $id, array $validated)` | Atualiza e retorna o model. |
| `delete(int\|string $id)` | Deleta. |

## Estrutura criada por `make:module`

```
app/Modules/{Modulo}/
├── Actions/
├── Controllers/
│   └── {Modulo}Controller.php
├── Events/
├── Jobs/
├── Listeners/
├── Models/
│   └── {Modulo}.php
├── Requests/
│   ├── Store{Modulo}Request.php
│   └── Update{Modulo}Request.php
├── Resources/
│   └── {Modulo}Resource.php
├── Routes/
│   ├── api.php
│   └── web.php
├── Rules/
├── Services/
│   └── {Modulo}Service.php
└── Views/
```

## Customização

### Publicando os stubs

Se quiser editar os templates usados na geração:

```bash
php artisan vendor:publish --tag=laravel-modules-stubs
```

Os arquivos vão para `stubs/` na raiz do projeto. O package usa primeiro os
stubs locais; se não existirem, cai nos do package.

Stubs disponíveis:

- `module-action.stub`
- `module-controller.stub`
- `module-event.stub`
- `module-job.stub`
- `module-listener.stub`
- `module-model.stub`
- `module-request.stub`
- `module-resource.stub`
- `module-route.stub`
- `module-rule.stub`
- `module-service.stub`

Placeholders disponíveis nos stubs:

- `{{ module }}` — namespace relativo (ex: `Users\Controllers\Auth`)
- `{{ target }}` — nome final da classe (ex: `LoginController`)
- `{{ module_lower }}` — nome do módulo em minúsculas (ex: `users`)

### Publicando o config

```bash
php artisan vendor:publish --tag=laravel-modules-config
```

Gera `config/modules.php`:

```php
return [
    'path' => app_path('Modules'),

    'folders' => [
        'Actions', 'Controllers', 'Models', 'Requests', 'Resources',
        'Rules', 'Events', 'Listeners', 'Jobs', 'Routes', 'Services', 'Views',
    ],

    'routes' => [
        'web' => [
            'enabled' => true,
            'middleware' => ['web'],
            'prefix' => null,
        ],
        'api' => [
            'enabled' => true,
            'middleware' => ['api'],
            'prefix' => 'api',
        ],
    ],
];
```

Opções:

- `path` — onde os módulos vivem.
- `folders` — pastas criadas por `make:module`.
- `routes.{web,api}.enabled` — desliga o auto-load se quiser.
- `routes.{web,api}.middleware` — middlewares aplicados ao grupo.
- `routes.{web,api}.prefix` — prefixo aplicado ao grupo. Suporta o token
  `{module}` que será substituído pelo nome do módulo em minúsculas.

## Licença

MIT
