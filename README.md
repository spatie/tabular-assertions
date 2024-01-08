# Write tabular assertions with Pest or PHPUnit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/tabular-assertions.svg?style=flat-square)](https://packagist.org/packages/spatie/tabular-assertions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/tabular-assertions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/tabular-assertions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/tabular-assertions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spatie/tabular-assertions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/tabular-assertions.svg?style=flat-square)](https://packagist.org/packages/spatie/tabular-assertions)

**🚧 This package is under development! Documentation is scarce but feel free to play around already.** 

Tabular assertions allow you to describe data in a Markdown table-like format and compare it to the actual data. This is especially useful when comparing large, ordered data sets like financial data or a time series. 

With Pest:

```php
test('it compares a table', function () use ($users) {
    $order = Order::factory()
        ->addItem('Pen', 2)
        ->addItem('Paper', 1)
        ->addItem('Pencil', 5)
        ->create();

        $items = $order->items
            ->map->only(['id', 'order_id', 'name', 'quantity']);

    expect($items)->toMatchTable('
        | #id | #order_id | name   | quantity |
        |  #1 |        #1 | Pen    |        2 |
        |  #2 |        #1 | Paper  |        1 |
        |  #3 |        #1 | Pencil |        5 |
    ');
});
```

With PHPUnit:

```php
use PHPUnit\Framework\TestCase;
use Spatie\TabularAssertions\PHPUnit\TabularAssertions;

class PHPUnitTest extends TestCase
{
    use TabularAssertions;

    public function test_it_contains_users(): void
    {
        $order = Order::factory()
            ->addItem('Pen', 2)
            ->addItem('Paper', 1)
            ->addItem('Pencil', 5)
            ->create();

        $items = $order->items
            ->map->only(['id', 'order_id', 'name', 'quantity']);
    
        $this->assertMatchesTable('
            | #id | #order_id | name   | quantity |
            |  #1 |        #1 | Pen    |        2 |
            |  #2 |        #1 | Paper  |        1 |
            |  #3 |        #1 | Pencil |        5 |
        ', $items);
    }
}
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/tabular-assertions.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/tabular-assertions)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/tabular-assertions
```

## Why tabular assertions?

Tabular assertions have two major benefits over other testing strategies: expectations are optimized for readability & failed assertions can display multiple errors at once.

**1. You can hand-write expectations that contain a lot of data and are optimized for readability.** Text-based tables are compact, allow you to compare the data in two dimensions.

The alternative would be to write multiple assertions.

```php
expect($items[0]['order_id'])->toBe($order->id);
expect($items[0]['name'])->toBeDate('Pen');
expect($items[0]['quantity'])->toBe(2);

expect($items[1]['order_id'])->toBe($order->id);
expect($items[1]['name'])->toBeDate('Paper');
expect($items[1]['quantity'])->toBe(1);

// …
```

Expectations require you to assert each property individually. This makes it hard to see all dates at a glance, and is less readable in general.

Associative arrays require a lot of repetition with labels.

```php
expect($items[0])->toBe([
    'order_id' => $order->id,
    'name' => 'Pen',
    'quantity' => 2,
]);

expect($items[1])->toBe([
    'order_id' => $order->id,
    'date' => 'Paper',
    'quantity' => 1,
]);

// etc…
```

Arrays without keys can't be aligned properly (manually maintained spaces would be striped by code style fixers). This becomes unclear when asserting multiple columns with different lengths.

```php
expect($items)->toBe([
    [$order->id, 'Pen', 2],
    [$order->id, 'Paper', 1],
    // …
]);
```

With tabular assertions, we get a compact, readable overview of the data, and because it's stored in a single string code style fixers won't reformat it.

```php
expect($items)->toMatchTable('
    | #id | #order_id | name   | quantity |
    |  #1 |        #1 | Pen    |        2 |
    |  #2 |        #1 | Paper  |        1 |
    |  #3 |        #1 | Pencil |        5 |
');
```

**2. Errors that can display multiple problems.** With separate expectations, tests fail on the first failed assertion which means you don't have the full picture (small issue vs. everything broken)

If you serialize two datasets to a table, you can get a nice output in a visual diff like PhpStorm's output when you use `assertEquals`.

In this assertions, you can see one value is wrong and one row is missing in one glance. With separate assertions, you only see the first error your test runner comes across.

<img width="698" alt="CleanShot 2023-02-09 at 14 48 38@2x" src="https://user-images.githubusercontent.com/1561079/217830800-e88953a5-446b-49d1-be7d-edfbb5484441.png">

This style of testing really shines when you have a lot of data to assert. This example has 9 rows and 9 columns, which means we're comparing 81 data points while keeping it all readable.

```php
expect($order->logs)->toLookLike("
    | type        | reason   | #product_id | #tax_id | #shipping_id | #payment_id | price | paid  | refunded |
    | product     | created  |       #1    |         |              |             | 80_00 | 80_00 |     0_00 |
    | tax         | created  |       #1    |      #1 |              |             |  5_00 |  5_00 |     0_00 |
    | tax         | created  |       #1    |      #2 |              |             | 10_00 | 10_00 |     0_00 |
    | shipping    | created  |       #1    |         |           #1 |             |  5_00 |  5_00 |     0_00 |
    | product     | paid     |       #1    |         |              |          #1 |  0_00 |  0_00 |     2_00 |
    | tax         | paid     |       #1    |      #1 |              |          #1 |  0_00 |  0_00 |     0_00 |
    | tax         | paid     |       #1    |      #2 |              |          #1 |  0_00 |  0_00 |     0_00 |
    | shipping    | paid     |       #1    |         |           #1 |          #1 |  0_00 |  0_00 |     0_00 |
");
```

## Usage

### Basic usage: Pest

With Pest, the plugin will be autoloaded and readily available. Use the custom `toMatchTable()` expectation to compare data with a table.

### Basic usage: PHPUnit

With PHPUnit, add the `Spatie\TabularAssertions\PHPUnit\TabularAssertions` trait to the tests you want to use tabular assertions with. Use `$this->assertMatchesTable()` to compare data with a table. 

### Dynamic values

Sometimes you want to compare data without actually comparing the exact value. For example, you want to assert that each person is in the same team, but don't know the team ID because the data is randomly seeded on every run. A column can be marked as "dynamic" by prefixing its name with a `#`. Dynamic columns will replace values with placeholders. A placeholder is unique for the value in the column. So a team with ID `123` would always be rendered as `#1`, another team `456` with `#2` etc.

For example, Sebastian & Freek are in team Spatie which has a random ID, and Christoph is in team Laravel with another random ID.

```
| name      | #team_id |
| Sebastian |       #1 |
| Freek     |       #1 |
| Christoph |       #2 |
```

## Inspiration & alternatives

The idea for this was inspired by Jest, which allows you to use a table as a data provider. https://maxoid.io/using-table-in-method-it.each-of-jest/

[Snapshot testing](https://github.com/spatie/phpunit-snapshot-assertions) is also closely related to this. But snapshots aren't always optimized for readability, are stored in a separate file (not alongside the test), and are hard to write by hand (no TDD).

## Testing

Tests are written with Pest. You can either use Pest's CLI or run `composer test` to run the suite.

```bash
composer test
```

In addition to tests, PhpStan statically analyses the code. Use `composer analyse` to run PhpStan. 

```bash
composer analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
