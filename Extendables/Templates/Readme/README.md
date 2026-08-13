## Project specification

- Laravel 11
- Composer 2
- Timezone: UTC

## System requirement

- PHP 8.2
- Postgres 15.2
- Redis 6

## Drivers and engines

- Queue driver: Redis
- Cache driver: Redis
- Storage: Amazon S3

## Dependencies

- [Laravel Sanctum for authentication](https://laravel.com/docs/11.x/sanctum)
- [Laravel Horizon for queue management](https://laravel.com/docs/11.x/horizon)
- [Laravel Telescope for debugging](https://laravel.com/docs/11.x/telescope)
- [PEST for testing](https://pestphp.com/)

## Structure

### Inspiration

- [Laravel beyond CRUD: Domain oriented Laravel](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Working with data](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Actions](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Models](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Testing domains](https://online.fliphtml5.com/pbudi/dfap/#p=7)
- [Effective Eloquent queries](https://laravel-news.com/effective-eloquent)
- [JSON API specification's query string format](https://jsonapi.org/format/#fetching)
- [State machines in Laravel](https://www.youtube.com/watch?v=1A1xFtlDyzU)

### Detail

app/

- Console/:
    - ArtisanCommand/registry.php: features-related Artisan commands registry
    - console.php: scheduled tasks and other Artisan commands registry
- Constants/: application's global constants
- Enums/: application's global enums
- Extendables/: base classes, interfaces, and traits for reuse across the entire application
- Features/: features related code
    - Actions/: reusable business and application logic
    - ApiResources/: API resources to transform data for JSON responses
    - ArtisanCommands/: custom artisan commands
    - Authorizers/: authorization logic
    - Cache/: caching related
    - Commands/: reusable write to database logic
    - Constants/: feature-related constants
    - Contexts/: manage request data and information
    - Enums/: feature-related enums
    - Exports/: exporters
    - Imports/: importers
    - Jobs/: queue jobs
    - Middlewares/: features related middlewares
    - Models/:
        - .php: represents a record in the data source. Models should only contain mutators, accessors, and no business
          logic
        - Relationships/: relationship interfaces for better typing and reuse of repetitive relationships
    - Notifications/: notifications
    - Queries/: reusable read from database logic
        - Filters/: filters to be applied based on request query string for index queries
        - Sorts/: sorts to be applied based on request query string for index queries
    - Responses/
        - ErrorCodeEnum.php: error codes of responses
        - Responder.php: build responses
    - StateMachines/: implementation of the state machine pattern
    - Validators/: validation logic
    - ValueObjects/: classes used to structure data instead of using unstructured and hard to predict arrays
- Http/: HTTP layer code
    - Middlewares/: application's global middlewares
    - Modules/:
        - Controller.php: controller for the HTTP module
        - routes.php: api routing definition for the HTTP module
- Ports/: external or third party services interaction

## Setup

### Install dependencies

#### For local development environment:

```shell
composer install
```

#### For production environment:

```shell
composer install --no-dev
```

### Config .env

```shell
cp .env.example .env
```

Important fields:

- APP_*
- DB_*
- MAIL_*
- FILESYSTEM_*
- AWS_*
- AUTH_*
- SCHEDULER_*

### Initialize

```
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan optimize
```

#### Start Laravel Horizon in the background

For the production environment, please follow
the [official documentation](https://laravel.com/docs/11.x/horizon#deploying-horizon).
For local development environment, you can start Horizon in background:

```shell
php artisan horizon &
```

#### Start the Scheduler

For production environment:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

For a local development environment, you can start the Scheduler in the background:

```shell
php artisan schedule:work &
```

#### Seed data for local development

```shell
php artisan db:seed --class=DevelopmentSeeder
```

## Code quality

To run both the code style fixer and tests at the same time:

```shell 
composer code-quality
```

### Code style fixer

```shell
./vendor/bin/pint
```

### Testing

- Config `PEST_*` fields in `.env` following the `.env.example` skeleton
- Prepare a database with a name following the `PEST_DB_DATABASE` config for running test
- Run tests with:

```shell 
composer pest-test
```

## Filesystem

When using dev server's s3 for local development, set the **filesystems.root_dir** config to **local**

## Laravel Horizon

To check Horizon status:

```shell
php artisan horizon:status
```

To stop or restart Horizon:

```shell
php artisan horizon:terminate
```

## Macros

Macros are registered in `ExtendableServiceProvider.php`

### Query builder macros

- whereEmpty
- whereNotEmpty

### Str macros

- replaceSlash
- hashSha256
- hashEachByteSha256

### Collection macros
- toEnumValues

## Conventions and standards

- [Eloquent model](./doc/model-conventions.md)