# Architecture and coding conventions

These rules apply to all PHP and API code. The examples use an invented `Article` feature; for an example of each
class type, see [Class types](#class-types).

## Principles

- Domain structure: feature code lives in `app/Features/{FeatureName}/`.
- Base classes: reusable components live in `app/Extendables/`.
- API routes: endpoints live in `app/Http/{ModuleName}/Endpoints/`.
- Query strings: follow the [JSON API specification](https://jsonapi.org/format/#fetching) (filter, sort, include,
  page, only).
- External services: third-party service integrations live in `app/Ports/`.

## Inspiration

- [Laravel beyond CRUD: Domain oriented Laravel](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Working with data](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Actions](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Models](https://online.fliphtml5.com/pbudi/dfap/#p=6)
- [Laravel beyond CRUD: Testing domains](https://online.fliphtml5.com/pbudi/dfap/#p=7)
- [Effective Eloquent queries](https://laravel-news.com/effective-eloquent)
- [JSON API specification's query string format](https://jsonapi.org/format/#fetching)
- [State machines in Laravel](https://www.youtube.com/watch?v=1A1xFtlDyzU)

## Directory layout

The class types below have examples in [Class types](#class-types); the example headings match the directory names.

### app/

- Console/:
    - ArtisanCommand/registry.php: registry of the features' Artisan command directories
    - Queue/: queue names and queue URL resolution
    - console.php: local scheduler and other Artisan command registrations
- Constants/: application-wide constants
- Enums/: application-wide enums
- Extendables/: base classes, interfaces, and traits reused across the whole application
- Features/: one directory per feature
    - Actions/: reusable business and application logic
    - ApiResources/: API resources that transform data for JSON responses
    - ArtisanCommands/: custom Artisan commands
    - Authorizers/: authorization checks
    - Cache/: cache stores
    - Commands/: reusable database writes
    - Constants/: feature constants
    - Contexts/: request-scoped data and information
    - Enums/: feature enums
    - Exceptions/: feature exceptions
    - Exports/: exporters
    - Imports/: importers
    - Jobs/: queue jobs
    - Mails/: mails
    - Middlewares/: feature middlewares
    - Models/: Eloquent models — table structure, configuration, accessors, mutators, and relationships only, with no
      business logic
        - Relationships/: optional relationship interfaces or traits shared by several models of the feature
          (application-wide ones live in `app/Extendables/`)
    - Notifications/: notifications
    - Providers/: feature service providers, for wiring specific to the feature rather than the whole application
    - Queries/: reusable database reads
        - Filters/: filters that index queries apply from the request query string
        - Sorts/: sorts that index queries apply from the request query string
    - Responses/:
        - {Feature}ErrorCodeEnum.php: error codes of the feature's responses
        - {Name}Responder.php: response builders
    - Rules/: custom validation rules (`ValidationRule` classes), used by Validators, Fields and other features
    - StateMachines/: state machine implementations
    - Validators/: request validation
        - Fields/: rule sets that several Validators share
    - ValueObjects/: typed data structures that replace loosely-structured arrays
- Http/: HTTP layer
    - MiddlewareConfiguration.php: application-wide middleware configuration
    - {ModuleName}/: one HTTP module per API audience or entry point (e.g. an admin API, a public API, webhooks)
        - {module}.php: the module's route definition
        - Endpoints/{EndpointGroup}/: the endpoint group's controller and routes file
    - Sharable/: controllers shared by several HTTP modules
- Ports/: integrations with external and third-party services

### tests/

- Architecture/: `arch()` preset rules (banned functions, security preset, ...)
- Console/: tests for `app/Console/` (queue URL resolution and registry)
- Extendables/: tests for `app/Extendables/`
- Factories/: test data factories on the house `Tests\TestFactory` abstract (`new()`/`create()`/`times()`) —
  independent of `database/factories/` and seeders, deliberately
- Features/: one subtree per `app/Features/{Feature}`, unit-level
- Http/: one subtree per HTTP module, one test file per endpoint
- Integration/: cross-cutting tests that drive several routes/Actions/ports to prove one contract (a
  middleware, a gate, a port's whole surface) holds across the board — not scoped to one endpoint or one action
- Ports/: tests for `app/Ports/` (external service integrations)

## PHP

### General

- Must use camelCase for variable and function names
- Must use PascalCase for class names and their file names
- Should use strong typing as much as possible
- Should declare parameter and return types explicitly
- Should use constructor property promotion
- Should prefer single quotes `' '` over double quotes `" "` for strings
- Must use curly braces for every control structure
- Prefer a PHPDoc block over an inline comment. A comment should explain a constraint the code cannot show

### Models

- Boolean flag columns: must not use the database `boolean` type. Store a flag as a `0`/`1` integer and cast it to
  `BoolIntValueEnum`. Write flags as `BoolIntValueEnum::TRUE`/`FALSE`, never PHP `true`/`false`.
- Model property access:
    - Database columns and accessors → use snake_case (e.g. `$article->is_pinned`, `$article->published_at`,
      `$article->created_at`).
    - Relationships → use camelCase (e.g. `$article->comments`, `$article->coAuthors`).
    - This separates data on this model (snake_case) from retrieving related data (camelCase).
    - Method definitions (accessor methods, relationship methods) follow standard PHP camelCase.
    - Model constants (e.g. `Article::IS_PINNED`) are for arrays or string values that need a dynamic key. To read
      data on a model instance, use the property directly (`$article->is_pinned`), not
      `$article->{Article::IS_PINNED}`.
    - Goal: all new code uses snake_case for accessors and columns. Existing camelCase accessor usages may remain,
      but when new code touches a line that uses one, refactor that usage to snake_case in the same change.
- Accessors (getters):
    - An accessor may only perform basic computation on the model's own attributes: casting, formatting, combining,
      or deriving a value that the row's data already fully determines.
    - If the value needs anything else — a `config()` value, another model, the current time compared against a
      business rule, a query — it is business logic and belongs in an Action (`app/Features/{Feature}/Actions/`),
      not in the model. Pass the model (or its relevant attributes) to the Action's `handle()` instead.
    - Inside an accessor, read sibling attributes as `$this->column_name` (snake_case, no braces), following Model
      property access above.
- Model sections order: organize a model class into these sections, in this order:
    1. Trait `use` statements.
    2. Table structure: the model's table and attributes as constants, for IDE typing and easier column maintenance.
    3. Configuration: casts, guarded, fillable, and hidden attributes.
    4. Mutators and accessors.
    5. Relationships.
- Model configuration: prefer the attributes in `Illuminate\Database\Eloquent\Attributes` for model configuration.
    - The Table-structure constants (`const string COLUMN = 'column_name';`) exist so the rest of the class —
      accessors, relationships, queries — never repeats a column name as a bare string. The Configuration section is
      the one exception: `#[Table]`'s `key:` argument, `#[Fillable]`, `#[Hidden]`, `casts()`, and any trait config
      that mirrors these must use plain string literals, never `self::CONST` references.
    - Reason: IDE and static-analysis tooling (e.g. Laravel Idea) generates a model's magic-property type hints by
      statically parsing the literal values passed to `#[Fillable]`/`#[Hidden]` and the literal keys of the `casts()`
      array. It cannot resolve a `self::CONST` reference, so the reference silently breaks autocompletion and type
      hinting for every attribute on the model.
    - The Table-structure constants stay declared and remain the source of truth for every other use: accessors,
      relationship methods, scopes, queries, other models, and tests.

Accessor example:

```php
// Allowed: derived only from the row's own attributes.
protected function displayTitle(): Attribute
{
    return Attribute::get(fn (): string => mb_trim($this->title).' ('.$this->status->label().')');
}

// Not allowed in the model: depends on the current time and a config value.
protected function isEditable(): Attribute
{
    return Attribute::get(
        fn (): bool => $this->published_at?->isAfter(now()->subDays(config('article.edit_days'))) ?? true,
    );
}

// Instead: an Action that takes the model.
readonly class CheckArticleIsEditableAction
{
    public function handle(Article $article): bool
    {
        return $article->published_at === null
            || $article->published_at->isAfter(now()->subDays(config('article.edit_days')));
    }
}
```

Configuration before (constants break IDE type hints):

```php
#[Table(name: 'articles', key: self::ID, keyType: 'int', incrementing: true, timestamps: true)]
#[Fillable([
    self::TITLE,
    self::IS_PINNED,
])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            self::IS_PINNED => BoolIntValueEnum::class,
            self::PUBLISHED_AT => 'immutable_datetime',
        ];
    }
}
```

Configuration after (plain string literals):

```php
#[Table(name: 'articles', key: 'id', keyType: 'int', incrementing: true, timestamps: true)]
#[Fillable([
    'title',
    'is_pinned',
])]
class Article extends Model
{
    protected function casts(): array
    {
        return [
            'is_pinned' => BoolIntValueEnum::class,
            'published_at' => 'immutable_datetime',
        ];
    }
}
```

### Enums

- Case names are `CAPITAL_SNAKE_CASE` (for example `NEEDS_REVIEW`). Backed values stay in the form the database or
  an external contract already uses.
- Int-backed enum values: unless an external constraint fixes a value (a value existing data already reads or
  writes, a third-party contract, or a number the specifications require verbatim), number cases from `0`. Choose
  deliberately which case gets `0` — the natural default, initial, or most common state — because `0` is falsy in
  PHP; don't let it fall to whichever case was written first.
- Enum methods: an enum's methods only display or print its values (e.g. `label()`, `englishName()`).
    - Anything that decides behavior — policy tables, permissions, mappings to other enums or types, predicates such
      as `isX()` — is business logic and belongs in an Action (`app/Features/{Feature}/Actions/`) that takes the enum
      as input, the same rule as for accessors.

Example:

```php
enum ArticleStatusEnum: int
{
    case DRAFT = 0; // the initial state gets 0
    case PUBLISHED = 1;
    case ARCHIVED = 2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        };
    }
}

// Behavior derived from the enum lives in an Action, not in an enum method such as isPubliclyVisible().
readonly class CheckArticleStatusIsPubliclyVisibleAction
{
    public function handle(ArticleStatusEnum $status): bool
    {
        return $status === ArticleStatusEnum::PUBLISHED;
    }
}
```

### Classes and constants

- Single public method classes: Query, Command, Action, and Authorizer instances expose only one public method,
  `handle()`, besides the constructor. Supporting logic stays in non-public methods.
    - Queries: `handle()` accepts an `Illuminate\Database\Query\Builder`, an `Illuminate\Database\Eloquent\Builder`
      (aliased as `EloquentBuilder`), or their union, depending on the operations required. It applies constraints to
      the supplied builder and returns that same instance; it does not create, clone, or execute a builder. Use
      `EloquentBuilder` when Eloquent-specific operations such as `whereHas()` are required. Three kinds of Query are
      exceptions, recognized by their names:
        - Count Queries (`Count…Query`) count the rows of the builder they receive, in one of two ways:
            - Execute and return the result: run the count on the supplied builder and return it as an `int`, or as a
              value object that holds several counts.
            - Build and return the builder: turn the supplied builder into the count query (counting selects,
              grouping) and return that same instance, like any other Query; the caller executes it.
        - Max Queries (`Max…Query`) find the largest value of one or more columns (or of a number derived from a
          column) in the rows of the builder they receive, in the same two ways as Count Queries:
            - Execute and return the result: run the `MAX` on the supplied builder and return it as a scalar, or as a
              value object that holds several maxima.
            - Build and return the builder: add the `MAX` selects to the supplied builder and return that same
              instance; the caller executes it.
        - Paginate Queries (`…PaginateQuery`) execute the supplied builder and return a paginator:
          `LengthAwarePaginator` or `CursorPaginator`.
        - None of these kinds creates its own main builder; each works on the one it receives.
        - Any other read that executes a builder to answer a question — whether a row exists, a boolean, a single
          model — is an Action: it creates the builder, narrows it with Queries, and executes it.
    - Row locks are Commands, not Queries: a class whose job is to take a row lock (`lockForUpdate()`,
      `sharedLock()`) and return the locked row is a Command named `Lock…Command`. The lock changes database state
      until the surrounding transaction ends, so it is not a pure read. It runs inside the caller's transaction and
      opens none of its own. An Action may still take a lock as one step of its own business logic.
    - Authorizers: `handle()` returns the result of the authorization check as a `bool` and, by default, aborts with
      a 403 when the check fails. It takes a trailing `bool $abortOnUnauthorized = true` parameter; callers that only
      need the result pass `abortOnUnauthorized: false`.
- Constant visibility: declare class constants `private` by default. Widen a constant only when code outside the
  class reads it — `protected` when only subclasses do, `public` when other classes do. Use in tests does not count:
  a test asserts the literal value (or its own test-file constant) instead of reaching into the class, so a constant
  that only tests read stays `private`.
    - Exempt: model Table-structure constants (see Model property access), which name the schema for the whole
      codebase and stay `public`. Interface constants are public by language rule.

Query example:

```php
readonly class GetPublishedArticleQuery
{
    public function handle(EloquentBuilder $builder): EloquentBuilder
    {
        return $builder
            ->where(Article::qualifiedColumn(Article::STATUS), ArticleStatusEnum::PUBLISHED)
            ->whereHas(Article::RELATION_AUTHOR);
    }
}

// The caller owns the builder and executes it.
$articles = $getPublishedArticleQuery->handle(Article::query())->get();
```

Count Query examples, one per option:

```php
// Execute and return the result.
readonly class CountArticleByPinnedQuery
{
    public function handle(EloquentBuilder $builder): ArticlePinnedCountsValueObject
    {
        $pinned = Article::qualifiedColumn(Article::IS_PINNED);

        $rows = $builder->toBase()
            ->select($pinned)
            ->selectRaw('COUNT(*) AS total')
            ->groupBy($pinned)
            ->get()
            ->keyBy(Article::IS_PINNED);

        return new ArticlePinnedCountsValueObject(
            pinned: (int) ($rows->get(BoolIntValueEnum::TRUE->value)->total ?? 0),
            unpinned: (int) ($rows->get(BoolIntValueEnum::FALSE->value)->total ?? 0),
        );
    }
}

// Build and return the builder.
readonly class CountArticleByStatusQuery
{
    public const string TOTAL = 'total';

    public function handle(EloquentBuilder $builder): EloquentBuilder
    {
        $status = Article::qualifiedColumn(Article::STATUS);

        return $builder
            ->select($status)
            ->selectRaw('COUNT(*) AS '.self::TOTAL)
            ->groupBy($status);
    }
}

// The caller executes it.
$totals = $countArticleByStatusQuery->handle(Article::query())
    ->toBase()
    ->pluck(CountArticleByStatusQuery::TOTAL, Article::STATUS);
```

Paginate Query example:

```php
readonly class OffsetPaginateQuery
{
    public function handle(EloquentBuilder|Builder $builder, int $pageSize, int $pageNumber): LengthAwarePaginator
    {
        return $builder->paginate(perPage: $pageSize, page: $pageNumber);
    }
}
```

Max Query example (execute and return the result):

```php
readonly class MaxArticleDisplayNumberQuery
{
    public function handle(EloquentBuilder $builder): int
    {
        return (int) $builder->max(Article::qualifiedColumn(Article::DISPLAY_NUMBER));
    }
}

// The caller owns the builder.
$nextDisplayNumber = $maxArticleDisplayNumberQuery->handle(Article::query()) + 1;
```

A read that answers a question is an Action, not a Query:

```php
readonly class CheckArticleHasCommentsAction
{
    public function __construct(
        private VisibleCommentQuery $visibleCommentQuery,
    ) {}

    public function handle(Article $article): bool
    {
        return $this->visibleCommentQuery
            ->handle(Comment::query()->where(Comment::qualifiedColumn(Comment::ARTICLE_ID), $article->id))
            ->exists();
    }
}
```

Authorizer example:

```php
readonly class PublishArticleAuthorizer
{
    public function __construct(
        private Responder $responder,
        private CheckUserHasPermissionAction $checkUserHasPermissionAction,
    ) {}

    public function handle(User $actor, bool $abortOnUnauthorized = true): bool
    {
        $authorized = $this->checkUserHasPermissionAction->handle($actor, PermissionEnum::ARTICLE_PUBLISH);

        abort_if(! $authorized && $abortOnUnauthorized, $this->responder->responseUnauthorized());

        return $authorized;
    }
}

// A caller that only needs the answer, e.g. to list the actions the user may take:
$canPublish = $publishArticleAuthorizer->handle($actor, abortOnUnauthorized: false);
```

Constant visibility example:

```php
final class ArticleIntConstants
{
    // A Validator and an ApiResource read it → public.
    public const int TITLE_MAX_LENGTH = 120;

    private function __construct() {}
}

readonly class ArticleSlugCache
{
    // Only this class reads it → private, even if a test asserts the TTL.
    private const int TTL_SECONDS = 600;
}
```

### Regular expressions

Avoid regular expressions unless no alternative fits. They invite ReDoS (catastrophic backtracking on crafted input)
and hard-to-predict bugs (Unicode and multibyte handling, anchors, escaping, greedy matches).

- Prefer plain string and multibyte functions (`mb_trim()`, `str_starts_with()`, `str_contains()`, `explode()`,
  `ctype_digit()`, `mb_convert_kana()`, …), Laravel's `Str` helpers that do not take a pattern, and built-in
  validation rules over `preg_*` calls and `Str::match()`, `Str::isMatch()`, or `Str::replaceMatches()`.
- In SQL, prefer `=`, `LIKE`, and string functions over `REGEXP` and `RLIKE`.
- Do not add a regular-expression route constraint unless the framework's other route constraints cannot express the
  rule.
- When a regular expression is the only reasonable option, keep it simple and anchored, never build it from user input
  without `preg_quote()`, bound the length of the subject first, and add a comment explaining why no alternative fits.

Example:

```php
// Before
$isNumeric = preg_match('/^\d+$/', $value) === 1;
$hasPrefix = preg_match('/^ART-/', $code) === 1;

// After
$isNumeric = ctype_digit($value);
$hasPrefix = str_starts_with($code, 'ART-');
```

### Exceptions and HTTP status

- 422 is reserved for request validation performed by Validator classes (`app/Features/{Feature}/Validators/`,
  through `Validator::make(...)->validate()` and validation rules).
- Outside Validators (Actions, Commands, Queries, Ports, Authorizers, …), never throw `ValidationException`
  (including `ValidationException::withMessages()`) or call `abort(422)` for a business-rule failure.
- Instead, throw a dedicated exception from `app/Features/{Feature}/Exceptions/` that implements
  `App\Extendables\Core\Http\Exception\ExtendableException`. Return the most specific 4xx status that fits (e.g. 401,
  403, 404, 409 for an already-existing resource, 429), or 400 when no other 4xx fits.

Example:

```php
use App\Extendables\Core\Http\Exception\ExtendableException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ArticleAlreadyPublishedException extends Exception implements ExtendableException
{
    public function httpStatusCode(): int
    {
        return Response::HTTP_CONFLICT;
    }

    public function httpErrorCode(): string
    {
        return 'article_already_published';
    }

    public function httpErrorMessage(): string
    {
        return 'The article is already published.';
    }
}

// In an Action, instead of ValidationException::withMessages(...) or abort(422):
if ($article->status === ArticleStatusEnum::PUBLISHED) {
    throw new ArticleAlreadyPublishedException;
}
```

### Transactions

- When you introduce or modify a `DB::transaction(...)`, trace the call graph up to the controller to find any
  transaction already open in the chain.
- If a caller already wraps the call in a transaction, do not open a nested one. Propagate the choice down through a
  `bool $useTransaction = true` parameter so callers can opt out.
- Caller responsibilities:
    - Callers with their own outer transaction pass `useTransaction: false`.
    - Callers without an outer transaction keep the default (`useTransaction: true`) and let the Action manage it.

Example:

```php
readonly class PublishArticleAction
{
    public function __construct(
        private UpdateArticleCommand $updateArticleCommand,
        private CreateArticleHistoryCommand $createArticleHistoryCommand,
    ) {}

    public function handle(Article $article, User $actor, bool $useTransaction = true): void
    {
        $run = function () use ($article, $actor): void {
            $this->updateArticleCommand->handle($article, [Article::STATUS => ArticleStatusEnum::PUBLISHED]);
            $this->createArticleHistoryCommand->handle($article, $actor);
        };

        $useTransaction ? DB::transaction($run, attempts: 3) : $run();
    }
}

// A caller that already holds a transaction opts out, so no nested transaction is opened.
DB::transaction(function () use ($articles, $actor): void {
    foreach ($articles as $article) {
        $this->publishArticleAction->handle($article, $actor, useTransaction: false);
    }
});
```

## Class types

The examples share one invented feature, `app/Features/Article/`, and one HTTP module, `app/Http/Admin/`. They omit
`use` lines unless the base class matters. For the rules these classes follow (single public `handle()`, exceptions,
transactions, …), see the [PHP](#php) rules above.

### Routes

A module route file sets the prefix, route name, and middleware shared by the module, then loads each endpoint
group's routes file through `RouteInvoker`. The endpoint group directory is the StudlyCase resource and the routes file
name uses the kebab-case resource, so `article_draft` loads `Endpoints/ArticleDraft/admin-article-draft-routes.php`.

```php
// app/Http/Admin/admin.php
Route::prefix('api/admin')
    ->as('api.admin.')
    ->middleware(['api', 'auth:admin'])
    ->group(function () {
        // Loads app/Http/Admin/Endpoints/Article/admin-article-routes.php
        RouteInvoker::invokeResourceRoute('article', app_path('Http/Admin'), 'admin');
    });
```

An endpoint group's routes file declares paths relative to the module prefix.

```php
// app/Http/Admin/Endpoints/Article/admin-article-routes.php
Route::controller(AdminArticleController::class)
    ->prefix('articles')
    ->name('articles.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('{article}/publish', 'publish')->name('publish');
    });
```

### Controllers

A controller only orchestrates: it authorizes, validates, calls an Action or Query, and hands the result to a
Responder. It extends `ApiController`, and a collaborator that only one method uses is injected into that method.

```php
class AdminArticleController extends ApiController
{
    public function __construct(
        private readonly ArticleResponder $articleResponder,
    ) {}

    public function publish(
        Request $request,
        Article $article,
        PublishArticleAuthorizer $publishArticleAuthorizer,
        PublishArticleValidator $publishArticleValidator,
        PublishArticleAction $publishArticleAction,
    ): JsonResponse {
        $actor = $request->user();
        $publishArticleAuthorizer->handle($actor);                     // 403 when not allowed

        $publishAt = $publishArticleValidator->handle($request->all()); // 422 when invalid

        $publishArticleAction->handle($article, $actor, $publishAt);

        return $this->articleResponder->responseNoContent();
    }
}
```

### Middlewares

A middleware guards a group of routes. It rejects a request by throwing an exception (see [Exceptions](#exceptions)),
not by building a response.

```php
readonly class RejectArchivedArticleMiddleware
{
    /**
     * @param  Closure(Request): Response  $next
     *
     * @throws ArticleArchivedException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $article = $request->route('article');

        if ($article instanceof Article && $article->status === ArticleStatusEnum::ARCHIVED) {
            throw new ArticleArchivedException;
        }

        return $next($request);
    }
}
```

### Validators

A Validator checks the raw request input and returns typed, normalized data. It calls
`Validator::make(...)->validate()`, which responds with 422 on failure.

```php
readonly class PublishArticleValidator
{
    /**
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public function handle(array $data): ?CarbonImmutable
    {
        $validated = Validator::make($data, [
            ArticleRequestFieldConstants::PUBLISH_AT => ['nullable', 'date', 'after:now'],
        ])->validate();

        $publishAt = $validated[ArticleRequestFieldConstants::PUBLISH_AT] ?? null;

        return $publishAt !== null ? CarbonImmutable::parse($publishAt) : null;
    }
}
```

#### Fields

When several classes check the same field, or the same group of related fields, put those rules in a Field class in
`Validators/Fields/`. Do not share rules through a trait, a base Validator, or one class calling a Validator's rules.

- Name the class after what it validates, with the `Field` suffix, for example `ArticleTitleField`.
- Give it one public static method, `rules()`. It returns the rules keyed by request field, ready to spread into
  `Validator::make()`.
- One Field covers one field, or one group of fields that always go together, such as an address and its parts.
- When callers differ, pass the difference as a parameter: the request key, whether the field is required, or a
  leading condition such as `exclude_unless`. Do not copy the Field to change it.
- A Field builds plain rules itself. A rule object that needs the container, such as one that queries the database,
  is injected into the Validator and passed to the Field as a parameter. A Field never calls `app()`.
- A Field may build on another Field, for example to reuse an address field under a different key.
- Keep request keys that several classes read in a constants class, for example `ArticleRequestFieldConstants`, not
  on a Validator.

```php
class ArticleTitleField
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            ArticleRequestFieldConstants::TITLE => ['required', 'string', 'max:'.ArticleIntConstants::TITLE_MAX_LENGTH],
        ];
    }
}

class ArticleSummaryField
{
    /**
     * @param  string  $presence  `required`, or `nullable` where the summary is optional.
     * @param  list<mixed>  $extraRules  Rules the caller adds, such as an injected banned-word rule.
     * @return array<string, mixed>
     */
    public static function rules(string $presence, array $extraRules = []): array
    {
        return [
            ArticleRequestFieldConstants::SUMMARY => [$presence, 'string', 'max:500', ...$extraRules],
        ];
    }
}

readonly class CreateArticleValidator
{
    public function __construct(
        private NoBannedWordRule $noBannedWordRule,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function handle(array $data): array
    {
        return Validator::make($data, [
            ...ArticleTitleField::rules(),
            ...ArticleSummaryField::rules('nullable', [$this->noBannedWordRule]),
        ])->validate();
    }
}
```

### Rules

A rule that Laravel's built-in rules cannot express is a `ValidationRule` class in the feature's `Rules/` directory,
not under `Validators/`, so a feature can own rules without having Validators of its own.

- Name the class after the condition it checks, with the `Rule` suffix, for example `NoBannedWordRule`.
- Fail with an error code from the validation error code enum, not with a sentence.
- Leave an empty or mistyped value to the field's own rules (`required`, `string`, ...) unless the rule is implicit.
- A rule that needs other fields implements `DataAwareRule`. Read the fields through a value object or an Action that
  several rules share, not through a trait.
- A rule with dependencies is resolved from the container and injected into the Validator; one without them is built
  with `new`.

```php
readonly class NoBannedWordRule implements ValidationRule
{
    public function __construct(
        private FindBannedWordAction $findBannedWordAction,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && $this->findBannedWordAction->handle($value) !== null) {
            $fail(ArticleErrorCodeEnum::CONTAINS_BANNED_WORD->value);
        }
    }
}
```

### Authorizers

See [Classes and constants](#classes-and-constants) for the full example.

### Contexts

A Context holds state for the current request. Register it as `scoped` so each request gets a fresh instance.

```php
class ArticlePreviewContext
{
    private ?string $previewToken = null;

    public function set(string $previewToken): void
    {
        $this->previewToken = $previewToken;
    }

    public function getPreviewToken(): ?string
    {
        return $this->previewToken;
    }
}

// In a service provider's register():
$this->app->scoped(ArticlePreviewContext::class);
```

### Actions

An Action holds one piece of business logic and composes Commands, Queries, Ports, and other Actions. For the
`useTransaction` pattern, see [Transactions](#transactions).

```php
readonly class ArchiveArticleAction
{
    public function __construct(
        private UpdateArticleCommand $updateArticleCommand,
    ) {}

    public function handle(Article $article): Article
    {
        if ($article->status === ArticleStatusEnum::ARCHIVED) {
            throw new ArticleArchivedException;
        }

        return $this->updateArticleCommand->handle($article, [Article::STATUS => ArticleStatusEnum::ARCHIVED]);
    }
}
```

### Commands

A Command performs one database write and returns the written model, or takes one row lock and returns the locked row
(see [Classes and constants](#classes-and-constants)).

A Command decides nothing. It does not read rows to choose what to write, count, branch on a read, or call another
Command. "Check, then write" and "read the IDs, then write each" are business logic, so they belong in an Action. The
Action narrows a builder with Queries, executes it, decides, and calls single-write Commands. For example,
`CapArticleDraftsPerAuthorAction` counts an author's drafts, locks the overflow rows, and passes their IDs to
`DeleteArticlesCommand`.

```php
class CreateArticleCommand
{
    /** @param  array<string, mixed>  $data */
    public function handle(array $data): Article
    {
        return Article::query()->create($data);
    }
}

// Runs inside the caller's transaction; the lock holds until it commits or rolls back.
class LockArticleForUpdateCommand
{
    public function handle(Article $article): ?Article
    {
        return Article::query()->whereKey($article->getKey())->lockForUpdate()->first();
    }
}
```

### Queries

A Query only adds constraints to the builder it receives; see [Classes and constants](#classes-and-constants).
Count, Max and Paginate Queries are the exceptions described there.
An index Query lists the JSON API `filter[...]` and `sort` fields it allows and lets `IndexQuery` apply them.

```php
readonly class IndexArticleQuery
{
    public function __construct(
        private IndexQuery $indexQuery,
        private ExactFilter $exactFilter,
        private ArticleStatusFilter $articleStatusFilter,
        private BasicSort $basicSort,
    ) {}

    public function handle(EloquentBuilder $builder): EloquentBuilder
    {
        return $this->indexQuery->handle(
            $builder,
            allowedFilters: [
                Article::AUTHOR_ID => $this->exactFilter,
                Article::STATUS => $this->articleStatusFilter,
            ],
            allowedSorts: [
                Article::ID => $this->basicSort,
                Article::PUBLISHED_AT => $this->basicSort,
            ],
        );
    }
}
```

A filter in `Queries/Filters/` handles one `filter[field]=...` condition.

```php
class ArticleStatusFilter extends Filter
{
    use IntegerFilterValuesParser;

    public function handle(EloquentBuilder|Builder $builder, FilterCondition $filterCondition): Builder|EloquentBuilder
    {
        $statuses = $this->integerValues($filterCondition, array_column(ArticleStatusEnum::cases(), 'value'));

        return $statuses === []
            ? $builder
            : $builder->whereIn(Article::qualifiedColumn(Article::STATUS), $statuses);
    }
}
```

A sort in `Queries/Sorts/` handles one `sort=field` or `sort=-field` entry.

```php
class PinnedFirstSort extends Sort
{
    public function handle(EloquentBuilder|Builder $builder, SortCondition $sortCondition): Builder|EloquentBuilder
    {
        return $builder
            ->orderBy(Article::qualifiedColumn(Article::IS_PINNED), SortDirectionEnum::DESC->value)
            ->orderBy($this->targetColumn ?? $sortCondition->field, $sortCondition->direction->value);
    }
}
```

### StateMachines

A state machine moves a model between statuses. A director picks the state class for the model's current status; the
abstract state rejects every transition, and each concrete state overrides only the transitions it allows.

```php
class ArticleStatusStateDirector
{
    public function __construct(private Application $app) {}

    public function handle(Article $article): ArticleStatusState
    {
        $state = match ($article->status) {
            ArticleStatusEnum::DRAFT => ArticleDraftStatusState::class,
            ArticleStatusEnum::PUBLISHED => ArticlePublishedStatusState::class,
            ArticleStatusEnum::ARCHIVED => ArticleArchivedStatusState::class,
        };

        return $this->app->makeWith($state, ['article' => $article]);
    }
}

abstract class ArticleStatusState
{
    public function __construct(
        protected Article $article,
        protected UpdateArticleCommand $updateArticleCommand,
    ) {}

    public function publish(): void
    {
        throw new ArticleStatusConflictException;
    }
}

class ArticleDraftStatusState extends ArticleStatusState
{
    public function publish(): void
    {
        $this->updateArticleCommand->handle($this->article, [Article::STATUS => ArticleStatusEnum::PUBLISHED]);
    }
}

// In an Action:
$this->articleStatusStateDirector->handle($article)->publish();
```

### Exceptions

See [Exceptions and HTTP status](#exceptions-and-http-status) for the full example.

### Models

See [Models](#models) for the full example.

### ValueObjects

A ValueObject replaces a loosely-typed array with `public readonly` properties. It extends `ValueObject`.

```php
class ArticleSummaryValueObject extends ValueObject
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?CarbonImmutable $publishedAt,
    ) {}
}
```

### Enums

See [Enums](#enums) for the full example.

### Constants

A constants class groups typed constants by type or purpose and cannot be instantiated.

```php
final class ArticleRequestFieldConstants
{
    /** Request field holding the scheduled publish date-time. */
    public const string PUBLISH_AT = 'publish_at';

    private function __construct() {}
}
```

### Cache

A cache store wraps one kind of cache entry behind domain-named methods. It builds keys with `CacheHelper` and stores
arrays, never objects.

```php
readonly class ArticleViewCountCache
{
    private const string NAMESPACE = 'article-view-count';

    private const int TTL_SECONDS = 3600;

    public function __construct(private CacheHelper $cacheHelper) {}

    public function put(int $articleId, int $viewCount): void
    {
        Cache::put($this->key($articleId), ['view_count' => $viewCount], self::TTL_SECONDS);
    }

    public function find(int $articleId): ?int
    {
        $payload = Cache::get($this->key($articleId));

        return is_array($payload) ? (int) $payload['view_count'] : null;
    }

    private function key(int $articleId): string
    {
        return $this->cacheHelper->generateCacheKey(namespace: self::NAMESPACE, identifier: (string) $articleId);
    }
}
```

### ApiResources

An ApiResource turns a model into response fields. It extends `InjectableJsonResource` and implements `handle()`, which
may take injected dependencies; `potentiallyMissing()` drops a field that the `only` query string did not request. A
collection resource only names its item resource.

- Must keep a response's keys independent of server-side state. When there is no data, send the key with `null`, or
  with an object whose fields are `null`. Do not leave the key out.
- May leave a key out only in these cases:
    - The client chose it: the `only` or `include` query string (`potentiallyMissing()`, `whenRelationRequested()`,
      `mergeWhenRelationRequested()`).
    - The actor lacks the permission that gates the key: return `PermissionGatedMissingValue`.
    - The record's state does not meet the display condition of the key's block: return
      `StatusConditionedMissingValue`.
- Must not return a bare `MissingValue`. Name every key that can be left out, and why, in the resource's PHPDoc.
- Should return an array or a collection of items through a `CollectionJsonResource` subclass that names its item
  resource in `$collects`, rather than mapping each item to an item resource (`->map(fn ($x) => new XResource($x))`).
  For a per-item argument, use `mapBy()`.

```php
/**
 * @mixin Article
 *
 * `internal_note` is left out unless the actor may read internal notes; `archive` is left out until the article is
 * archived.
 */
class ArticleResource extends InjectableJsonResource
{
    /** @return array<string, mixed> */
    public function handle(ArticlePolicy $policy): array
    {
        return [
            'id' => $this->id,
            'title' => $this->potentiallyMissing('title', fn () => $this->title),
            'status' => $this->potentiallyMissing('status', fn () => $this->status->value),
            'is_pinned' => $this->potentiallyMissing('is_pinned', fn () => $this->is_pinned->value),
            // Sent as null until the article is published, never left out.
            'published_at' => $this->potentiallyMissing('published_at', fn () => $this->published_at),
            'tags' => $this->potentiallyMissing('tags', fn () => new TagCollectionResource($this->tags)),
            'internal_note' => $policy->readsInternalNote()
                ? $this->potentiallyMissing('internal_note', fn () => $this->internal_note)
                : new PermissionGatedMissingValue,
            'archive' => $this->status === ArticleStatusEnum::ARCHIVED
                ? $this->potentiallyMissing('archive', fn () => ['archived_at' => $this->archived_at])
                : new StatusConditionedMissingValue,
        ];
    }
}

class ArticleCollectionResource extends CollectionJsonResource
{
    public $collects = ArticleResource::class;
}
```

### Responses

A Responder builds the feature's responses, with one method per response shape. It extends `Responder`, and its error
codes come from the feature's `ErrorCodeEnum`.

```php
class ArticleResponder extends Responder
{
    public function responseListArticle(LengthAwarePaginator $articles): JsonResponse
    {
        return $this->responseBuilder->responseSuccess(new ArticleCollectionResource($articles));
    }

    public function responseSlugConflict(): JsonResponse
    {
        return $this->responseBuilder->responseConflict(
            ArticleErrorCodeEnum::SLUG_ALREADY_TAKEN->value,
            'The slug is already used by another article.',
        );
    }
}

enum ArticleErrorCodeEnum: string
{
    case SLUG_ALREADY_TAKEN = 'slug_already_taken';
}
```

### Jobs

A queued job runs work outside the request. It implements `ShouldQueue` (and `ShouldBeEncrypted` when its payload holds
personal data), picks its queue in the constructor, and receives its dependencies in `handle()`. Dispatch it from an
Action.

```php
class GenerateArticleThumbnailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $articleId)
    {
        $this->onQueue(QueueManager::fileProcessingQueueUrl());
    }

    public function handle(GenerateArticleThumbnailAction $generateArticleThumbnailAction): void
    {
        $article = Article::query()->find($this->articleId);

        if ($article !== null) {
            $generateArticleThumbnailAction->handle($article);
        }
    }
}

// In an Action:
GenerateArticleThumbnailJob::dispatch($article->id);
```

### Mails

A mail extends `ExtendableMailable`, queues on the notification queue, and is sent after the surrounding transaction
commits. Send it through `MailPort` instead of constructing it.

A mail receives the Actions it needs through its constructor, after its data. The sender injects those Actions into
its own constructor and passes them to `MailPort` with the mail's other arguments. A queued mail is serialized with
the Actions it holds. Keep a change to such an Action's constructor compatible with mails already in the queue, or
drain the queue before you deploy the change.

```php
class ArticlePublishedMail extends ExtendableMailable
{
    public function __construct(
        public readonly string $articleTitle,
        private readonly FormatArticleTitleAction $formatArticleTitleAction,
    ) {
        $this->onQueue(QueueManager::notificationQueueUrl());
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->brandedSubject('Your article is published'));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.article-published', with: [
            'articleTitle' => $this->formatArticleTitleAction->handle($this->articleTitle),
        ]);
    }
}

// In an Action that injects FormatArticleTitleAction:
$this->mailPort->queueSend(
    $author->email,
    ArticlePublishedMail::class,
    $article->title,
    $this->formatArticleTitleAction,
);
```

### ArtisanCommands

An Artisan command extends `Command`, delegates to one Action, and returns an exit code.
`app/Console/ArtisanCommand/registry.php` registers the commands in each feature's `ArtisanCommands/` directory.

```php
class PurgeArchivedArticlesArtisanCommand extends Command
{
    protected $signature = 'articles:purge-archived';

    protected $description = 'Deletes articles archived more than a year ago.';

    public function handle(PurgeArchivedArticlesAction $purgeArchivedArticlesAction): int
    {
        $deleted = $purgeArchivedArticlesAction->handle();
        $this->info("Deleted {$deleted} article(s).");

        return self::SUCCESS;
    }
}
```

### Exports

An exporter writes a file from a `Generator` of rows, so a large export never loads every record at once. The header
row comes from a backed enum.

```php
readonly class ArticleCsvExport
{
    public function __construct(
        private WriteCsvFileAction $writeCsvFileAction,
    ) {}

    public function handle(ArticleExportConditionValueObject $condition): string
    {
        $headers = array_map(
            fn (HeaderArticleCsvEnum $header): string => $header->value,
            HeaderArticleCsvEnum::cases(),
        );

        return $this->writeCsvFileAction->handle('articles', $headers, $this->rows($condition));
    }

    /** @return Generator<int, list<string|null>> */
    private function rows(ArticleExportConditionValueObject $condition): Generator
    {
        foreach (Article::query()->where(Article::STATUS, $condition->status)->lazyById() as $article) {
            yield [(string) $article->id, $article->title, $article->published_at?->toDateString()];
        }
    }
}
```

### Providers

A feature service provider holds wiring that belongs only to that feature, such as rate limiters. List it in
`bootstrap/providers.php`.

```php
class ArticleServiceProvider extends ServiceProvider
{
    public const string PUBLISH_RATE_LIMITER = 'article-publish';

    private const int PUBLISH_PER_MINUTE = 10;

    public function boot(): void
    {
        RateLimiter::for(
            self::PUBLISH_RATE_LIMITER,
            fn (Request $request): Limit => Limit::perMinute(self::PUBLISH_PER_MINUTE)
                ->by((string) $request->user()?->id),
        );
    }
}
```

### Ports

A Port is an interface to one external service. It has a real implementation and a dummy for tests, bound by
environment, and its methods return a result object instead of throwing on an expected failure.

```php
interface TranslationPort
{
    public function translate(string $text, string $targetLanguage): TranslationResult;
}

class SdkTranslationPort implements TranslationPort
{
    public function __construct(private TranslationClient $client) {}

    public function translate(string $text, string $targetLanguage): TranslationResult
    {
        try {
            return TranslationResult::success($this->client->translate($text, $targetLanguage));
        } catch (TranslationServiceException $exception) {
            return TranslationResult::failure(new TranslationErrorValueObject(
                TranslationErrorCodeEnum::SERVICE_UNAVAILABLE,
                $exception->getMessage(),
            ));
        }
    }
}

// In a service provider's register():
$this->app->bind(TranslationPort::class, fn () => $this->app->environment('testing')
    ? new DummyTranslationPort
    : $this->app->make(SdkTranslationPort::class));
```

## Configuration

- Read environment values with `env()` only in `config/*.php`. Application code reads `config()`.
- Store timestamps in UTC.

## RESTful API

- Must follow the [JSON API specification](https://jsonapi.org/) for request query strings
- Must not create GET endpoints that modify data
- Must not create GET endpoints that use or require a request body
- Must not use query strings or route parameters as input to modify data; use them only to get or target data
- Should use `snake_case` for response fields and request parameters
- Must use `0`/`1` (int) for booleans in request input and in responses, never JSON `true`/`false`.
    - Request input: validate a flag with `'integer', Rule::enum(BoolIntValueEnum::class)`, not Laravel's `boolean`
      rule, which also accepts `true`, `false`, `"1"`, and `"0"`.
    - Responses: for a `BoolIntValueEnum`-cast column, return its `->value`; map a derived flag (e.g. a `withCount`
      result) to `BoolIntValueEnum::TRUE->value` or `BoolIntValueEnum::FALSE->value`. A nullable flag stays `null`
      when its source is missing.
- Should avoid creating multi-purpose endpoints

Boolean example:

```php
// Request input, in a Validator's rules
$rules = [
    'is_pinned' => ['required', 'integer', Rule::enum(BoolIntValueEnum::class)],
];

// Response, in an ApiResource's handle()
return [
    'is_pinned' => $article->is_pinned->value, // BoolIntValueEnum-cast column
    'has_comments' => $article->comments_count > 0 // withCount() result
        ? BoolIntValueEnum::TRUE->value
        : BoolIntValueEnum::FALSE->value,
];
```

## Others

- Must separate words in route paths with `-`, e.g. `/user/get-user-info`
- Must give variables and functions easy-to-understand names; a long, clear name is better than a short, ambiguous one
- Must encrypt data stored in local storage and session storage
- Must document the application setup process, environment configuration, and dependencies in the project's README
- Should prefer the ternary operator (`?:`) over `if-else` for short, simple logic
- Should avoid nesting `if-else`, `for`, and `while` blocks; prefer guard clauses that return or throw early
