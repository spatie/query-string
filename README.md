# QueryString

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/query-filter.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)
[![Build Status](https://img.shields.io/travis/spatie/query-filter/master.svg?style=flat-square)](https://travis-ci.org/spatie/:package_name)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/query-filter.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/:package_name)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/query-filter.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)

Work with query strings

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

### Filters

#### Toggleable filters

```php
# / > /?toggle

$queryString->filter('toggle');
```

#### Single value filters

```php
# / > /?single=a

$queryString->filter('single', 'a');
```

```php
# /?single=a > /?single=b

$queryString->filter('single', 'b');
```

```php
# /?single=a > /?

$queryString->filter('single', 'a');
```

#### Multi value filters

```php
# / > /?multi[]=a&multi[]=b

$queryString->filter('multi[]', 'a');
$queryString->filter('multi[]', 'b');
```

```php
# /?multi[]=a&multi[]=b > /?multi[]=a

$queryString->filter('multi[]', 'b');
```

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

```
# /?multi[]=a

$queryString->isActive('multi[]'); # true
$queryString->isActive('multi[]', 'a'); # true
$queryString->isActive('multi[]', 'b'); # false
```

```
# /?single=a

$queryString->isActive('single'); # true
$queryString->isActive('single', 'a'); # true
$queryString->isActive('single', 'b'); # false
```

```
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

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
