# Write tabular assertions with Pest or PHPUnit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/tabular-assertions.svg?style=flat-square)](https://packagist.org/packages/spatie/tabular-assertions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/tabular-assertions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/tabular-assertions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/tabular-assertions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spatie/tabular-assertions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/tabular-assertions.svg?style=flat-square)](https://packagist.org/packages/spatie/tabular-assertions)

**🚧 This package is under development! Documentation is scarce and it isn't published on Packagist yet but feel free to play around already.** 

Tabular assertions allow you to describe data in a Markdown table-like format and compare it to the actual data. This is especially useful when comparing large, ordered data sets like financial data or a time series. 

With Pest:

```php
$users = [
    ['id' => 20, 'name' => 'John Doe', 'email' => 'john@doe.com'],
    ['id' => 1245, 'name' => 'Jane Doe', 'email' => 'jane@doe.com'],
];

test('it compares a table', function () use ($users) {
    $order = Order::factory()
        ->addItem('Pen', 2)
        ->addItem('Paper', 1)
        ->addItem('Pencil', 5)
        ->create();

    expect($order->items)->toMatchTable('
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
    
        $this->assertMatchesTable('
            | #id | #order_id | name   | quantity |
            |  #1 |        #1 | Pen    |        2 |
            |  #2 |        #1 | Paper  |        1 |
            |  #3 |        #1 | Pencil |        5 |
        ', $order->items);
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
