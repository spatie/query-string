# QueryString

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/query-string.svg?style=flat-square)](https://packagist.org/packages/spatie/query-string)
![run-tests](https://github.com/spatie/query-string/workflows/run-tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/query-string.svg?style=flat-square)](https://packagist.org/packages/spatie/query-string)

Work with query strings

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/query-string.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/query-string)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/query-string
```

## Usage

``` php
use Spatie\QueryString\QueryString;

$queryString = new QueryString($uri);
```

### Toggle parameters

#### A single toggle

```php
# / > /?toggle

$queryString->toggle('toggle');
```

#### Toggle a value

```php
# / > /?single=a

$queryString->toggle('single', 'a');
```

```php
# /?single=a > /?single=b

$queryString->toggle('single', 'b');
```

```php
# /?single=a > /?

$queryString->toggle('single', 'a');
```

#### Toggle multiple values

```php
# / > /?multi[]=a&multi[]=b

$queryString->toggle('multi[]', 'a');
$queryString->toggle('multi[]', 'b');
```

```php
# /?multi[]=a&multi[]=b > /?multi[]=a

$queryString->toggle('multi[]', 'b');
```

### Filter

Filtering the query string will use the JSON API filter syntax.

```php
# / > /?filter[field]=a

$queryString->filter('field', 'a');
```

```php
# / > /?filter[field][]=b

$queryString->filter('field[]', 'b');
```

### Sort

Sorting the query string will use the JSON API sort syntax.
At the moment only single sorts are supported.

```php
# / > /?sort=field > /?sort=-field > /?sort=field

$queryString->sort('field');
$queryString->sort('field');
$queryString->sort('field');
```

### Pagination

There's built-in support for pagination:

```php
$queryString->page(10); # /?page=10
$queryString->nextPage(); # /?page=11
$queryString->previousPage(); # /?page=9
$queryString->resetPage(); # /?

$queryString->isCurrentPage(1); # true
```

Note that changing any other value on the query string, will reset the page too.

### Other useful methods

#### Base URL

Casting a `QueryString` to a string will generate the URL. 
You can choose to use a different base URL like so:

```php
$queryString->withBaseUrl('https://other.url');
```

#### Clear a parameter

```php
# /?toggle > /

$queryString->clear('toggle');
```

```php
# /?single=b > /

$queryString->clear('single');
```

```php
# /?multi[]=a&multi[]=b > /

$queryString->clear('multi[]');
```

#### Active parameter or not

```php
# /?multi[]=a

$queryString->isActive('multi[]'); # true
$queryString->isActive('multi[]', 'a'); # true
$queryString->isActive('multi[]', 'b'); # false
```

```php
# /?single=a

$queryString->isActive('single'); # true
$queryString->isActive('single', 'a'); # true
$queryString->isActive('single', 'b'); # false
```

```php
# /?toggle

$queryString->isActive('toggle'); # true
```

### Laravel support

A separate Laravel package will be added in the future.
The Laravel package will use this one under the hood and implement the JSON API spec.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
