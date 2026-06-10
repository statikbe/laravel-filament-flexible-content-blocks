<?php

use Filament\Forms\Components\Select;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Support\TestableType;

/*
|--------------------------------------------------------------------------
| Locale-aware search & ordering for AbstractType select fields.
|
| Translatable columns are stored as JSON (e.g. {"en": "...", "nl": "..."}).
| Searching/ordering must operate on the active locale's *value* rather than
| the raw JSON document, on both MySQL and PostgreSQL. The test database is
| SQLite, which also supports JSON arrow extraction, so the behaviour is
| exercised end-to-end here.
|--------------------------------------------------------------------------
*/

afterEach(function () {
    Mockery::close();
});

/**
 * Build a Select component test double that only answers the few methods the
 * search/options closures call. The Livewire double has no
 * getActiveSchemaLocale(), so resolveLocale() falls back to the app locale.
 */
function fakeSelectComponent(): Select
{
    // getLivewire()'s return type is an intersection of Livewire\Component and
    // HasSchemas. The double has no getActiveSchemaLocale(), so resolveLocale()
    // falls back to the app locale.
    $livewire = Mockery::mock(Component::class, HasSchemas::class);

    $component = Mockery::mock(Select::class);
    $component->shouldReceive('getLivewire')->andReturn($livewire);
    $component->shouldReceive('getOptionsLimit')->andReturn(50);
    $component->shouldReceive('optionsLimit')->andReturnSelf();

    return $component;
}

it('builds a JSON arrow reference for translatable columns only', function () {
    $type = TestableType::make(TranslatablePage::class);
    $translatable = new TranslatablePage;
    $plain = new Page;

    // Translatable attribute + locale -> arrow path.
    expect($type->exposeResolveColumnReference($translatable, 'title', 'en'))->toBe('title->en');

    // Non-translatable attribute on a translatable model -> untouched.
    expect($type->exposeResolveColumnReference($translatable, 'code', 'en'))->toBe('code');

    // No locale -> untouched even for a translatable attribute.
    expect($type->exposeResolveColumnReference($translatable, 'title', null))->toBe('title');

    // Non-translatable model -> untouched.
    expect($type->exposeResolveColumnReference($plain, 'title', 'en'))->toBe('title');
});

it('filters configured search columns down to the ones that exist on the table', function () {
    $type = TestableType::make(TranslatablePage::class)
        ->searchColumns(['title', 'does_not_exist', 'overview_title']);

    $columns = $type->exposeGetExistingSearchColumns(TranslatablePage::query());

    expect($columns)->toBe(['title', 'overview_title']);
});

it('orders translatable records by the active locale value without erroring on JSON columns', function () {
    $first = TranslatablePage::factory()->create(['title' => ['en' => 'Zebra', 'nl' => 'Aap']]);
    $second = TranslatablePage::factory()->create(['title' => ['en' => 'Apple', 'nl' => 'Zwaan']]);

    $type = TestableType::make(TranslatablePage::class);

    $enOrder = $type->exposeApplyDefaultOrder(TranslatablePage::query(), 'en')->pluck('id')->all();
    expect($enOrder)->toBe([$second->id, $first->id]); // Apple < Zebra

    $nlOrder = $type->exposeApplyDefaultOrder(TranslatablePage::query(), 'nl')->pluck('id')->all();
    expect($nlOrder)->toBe([$first->id, $second->id]); // Aap < Zwaan
});

it('orders case-insensitively', function () {
    $lower = TranslatablePage::factory()->create(['title' => ['en' => 'apple', 'nl' => 'x']]);
    $upper = TranslatablePage::factory()->create(['title' => ['en' => 'Banana', 'nl' => 'y']]);

    $type = TestableType::make(TranslatablePage::class);

    $order = $type->exposeApplyDefaultOrder(TranslatablePage::query(), 'en')->pluck('id')->all();

    // Case-sensitive ordering would put "Banana" (uppercase B) before "apple".
    expect($order)->toBe([$lower->id, $upper->id]);
});

it('does not override an order that is already applied', function () {
    $type = TestableType::make(TranslatablePage::class);

    $query = TranslatablePage::query()->orderBy('id', 'desc');
    $type->exposeApplyDefaultOrder($query, 'en');

    expect($query->getQuery()->orders)->toHaveCount(1)
        ->and($query->getQuery()->orders[0]['column'])->toBe('id')
        ->and($query->getQuery()->orders[0]['direction'])->toBe('desc');
});

it('searches the active locale value of a translatable column', function () {
    app()->setLocale('en');

    $apple = TranslatablePage::factory()->create(['title' => ['en' => 'Apple pie recipe', 'nl' => 'Appeltaart recept']]);
    $banana = TranslatablePage::factory()->create(['title' => ['en' => 'Banana bread', 'nl' => 'Bananenbrood']]);

    $type = TestableType::make(TranslatablePage::class)->searchColumns(['title']);

    $results = ($type->getSearchResultsUsing)(fakeSelectComponent(), 'apple');

    expect(array_keys($results))->toContain($apple->id)
        ->and(array_keys($results))->not->toContain($banana->id);
});

it('does not match a term that only exists in a non-active locale', function () {
    app()->setLocale('en');

    $apple = TranslatablePage::factory()->create(['title' => ['en' => 'Apple pie recipe', 'nl' => 'Appeltaart recept']]);

    $type = TestableType::make(TranslatablePage::class)->searchColumns(['title']);

    // "recept" only appears in the Dutch value; with the English locale active
    // it must not match (proving we search the locale value, not raw JSON).
    $results = ($type->getSearchResultsUsing)(fakeSelectComponent(), 'recept');
    expect($results)->toBeEmpty();

    // Switching the active locale to Dutch makes it match.
    app()->setLocale('nl');
    $results = ($type->getSearchResultsUsing)(fakeSelectComponent(), 'recept');
    expect(array_keys($results))->toContain($apple->id);
});

it('searches plain string columns on non-translatable models', function () {
    $matching = Page::factory()->create(['title' => 'Apple pie recipe']);
    $other = Page::factory()->create(['title' => 'Banana bread']);

    $type = TestableType::make(Page::class)->searchColumns(['title']);

    $results = ($type->getSearchResultsUsing)(fakeSelectComponent(), 'apple');

    expect(array_keys($results))->toContain($matching->id)
        ->and(array_keys($results))->not->toContain($other->id);
});

it('compiles locale-aware SQL for postgres and mysql', function () {
    // The test database is SQLite; this locks in the generated SQL for the other
    // supported drivers by compiling against their grammars. toSql() does not
    // open a connection, so no live MySQL/PostgreSQL server is needed.
    config()->set('database.connections.pgsql', [
        'driver' => 'pgsql', 'host' => '127.0.0.1', 'database' => 'x', 'username' => 'x', 'password' => 'x', 'prefix' => '',
    ]);
    config()->set('database.connections.mysql', [
        'driver' => 'mysql', 'host' => '127.0.0.1', 'database' => 'x', 'username' => 'x', 'password' => 'x', 'prefix' => '',
    ]);

    $type = TestableType::make(TranslatablePage::class);
    $model = new TranslatablePage;
    $ref = $type->exposeResolveColumnReference($model, 'title', 'en');

    // PostgreSQL: extract the locale via ->> (text), order case-insensitively
    // with lower(), and match case-insensitively with ilike.
    $pgOrder = $type->exposeApplyDefaultOrder(TranslatablePage::on('pgsql')->newQuery(), 'en')->toSql();
    expect($pgOrder)->toContain('lower("title"->>\'en\')');
    $pgWhere = TranslatablePage::on('pgsql')->newQuery()->whereLike($ref, '%x%')->toSql();
    expect($pgWhere)->toContain('"title"->>\'en\'')->toContain('ilike');

    // MySQL: extract the locale via json_unquote(json_extract(...)), order with
    // lower(), and match with like (case-insensitive via collation).
    $myOrder = $type->exposeApplyDefaultOrder(TranslatablePage::on('mysql')->newQuery(), 'en')->toSql();
    expect($myOrder)->toContain('lower(json_unquote(json_extract(`title`, \'$."en"\')))');
    $myWhere = TranslatablePage::on('mysql')->newQuery()->whereLike($ref, '%x%')->toSql();
    expect($myWhere)->toContain('json_unquote(json_extract(`title`')->toContain('like ?');
});

it('returns no results when no configured search column exists on the table', function () {
    // Guards against an empty where() group, which SQL treats as "match
    // everything" and would otherwise return every record.
    TranslatablePage::factory()->create(['title' => ['en' => 'Apple pie recipe', 'nl' => 'Appeltaart recept']]);

    $type = TestableType::make(TranslatablePage::class)->searchColumns(['does_not_exist']);

    $results = ($type->getSearchResultsUsing)(fakeSelectComponent(), 'apple');

    expect($results)->toBeEmpty();
});

it('matches across a mix of translatable and plain columns in one search', function () {
    app()->setLocale('en');

    // "code" is a plain (non-translatable) column; "title" is translatable.
    $byTitle = TranslatablePage::factory()->create([
        'title' => ['en' => 'Apple pie recipe', 'nl' => 'Appeltaart recept'],
        'code' => 'fruit-banana',
    ]);
    $byCode = TranslatablePage::factory()->create([
        'title' => ['en' => 'Something else', 'nl' => 'Iets anders'],
        'code' => 'dessert-apple',
    ]);
    $neither = TranslatablePage::factory()->create([
        'title' => ['en' => 'Bread loaf', 'nl' => 'Brood'],
        'code' => 'bakery-bread',
    ]);

    $type = TestableType::make(TranslatablePage::class)->searchColumns(['title', 'code']);

    $results = array_keys(($type->getSearchResultsUsing)(fakeSelectComponent(), 'apple'));

    expect($results)->toContain($byTitle->id)  // matched via translatable title
        ->and($results)->toContain($byCode->id) // matched via plain code column
        ->and($results)->not->toContain($neither->id);
});
