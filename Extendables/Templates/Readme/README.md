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

- Enums/: application's global enums
- Extendables/: base classes, interfaces, and traits for reuse across the entire application. It has its own README.md
  in the directory
- Features/: features related code
    - Actions/: reusable business and application logic
    - Authorizers/: authorization logic
    - ArtisanCommands/: custom artisan commands
    - Validators/: validation logic
    - Jobs/: queue jobs
    - Notifications/: notifications
    - Exports/: exporters
    - Imports/: importers
    - ValueObjects/: classes used to structure data instead of using unstructured and hard to predict arrays
    - Cache/: caching related
    - StateMachines/: implementation of the state machine pattern
    - ApiResources/: API resources to transform data for JSON responses
    - Middlewares/: features related middlewares
    - Responses/
        - ErrorCodeEnum.php: error codes of responses
        - Responder.php: build responses
    - Enums/: feature related enums
    - Commands/: reusable write to database logic
    - Queries/: reusable read from database logic
        - Filters/: filters to be applied based on request query string for index queries
        - Sorts/: sorts to be applied based on request query string for index queries
    - Models/:
        - Relationships/: relationship interfaces for better typing and reuse of repetitive relationships
        - .php: represents a record in the data source. Models should ony contain mutators, accessors and no business
          logic
- Http/: HTTP layer code
    - Api/:
        - Controller.php: controller for api endpoints
        - routes.php: api routing definition
    - Web/: controllers for web pages
    - Middleware/: application's middlewares
    - Requests/
        - States/: manage request data and information
            - Headers/: states from headers
- Ports/: external or third party services interaction

## Setup

### Install dependencies

#### For local development environment:

```
composer install
```

#### For production environment:

```
composer install --no-dev
```

### Config .env

```
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

#### Start Laravel Horizon in background

For production environment, please follow
the [official documentation](https://laravel.com/docs/11.x/horizon#deploying-horizon).
For local development environment, you can start Horizon in background:

```
php artisan horizon &
```

#### Start the Scheduler

For production environment:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

For local development environment, you can start the Scheduler in background:

```
php artisan schedule:work &
```

#### Seed data for local development

```
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

```
php artisan horizon:status
```

To stop or restart Horizon:

```
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

## Conventions and standards

### Model

A Model class file should be organized into sections with the following order:

- Using traits section.
- ***Table structure*** section defining the Model's table and the Model's attributes as constants. This is intended for
  more convenient typing in IDE and easier maintenance and updating of table's columns.
- ***Configuration*** section defining the Model's casts, guarded, fillable, and hidden attributes.
- ***Mutators & Accessors*** section.
- ***Relationship*** section.