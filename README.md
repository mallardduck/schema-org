# A Fluent Builder For Schema.org Types And ld+json Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/schema-org.svg?style=flat-square)](https://packagist.org/packages/spatie/schema-org)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/schema-org/run-tests?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/spatie/schema-org/actions?query=workflow%3Arun-tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/schema-org.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/schema-org)
[![StyleCI](https://styleci.io/repos/74684096/shield?branch=master)](https://styleci.io/repos/74684096)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/schema-org.svg?style=flat-square)](https://packagist.org/packages/spatie/schema-org)

`spatie/schema-org` provides a fluent builder for **all** Schema.org types and their properties. The code in `src` is generated from Schema.org's [RDFa standards file](https://github.com/schemaorg/schemaorg/blob/master/data/schema.rdfa), so it provides objects and methods for the entire core vocabulary. The classes and methods are also fully documented as a quick reference.

------

## NOTICE

> This package supports [schema.org:v7.04](https://schema.org/version/7.04). 
> Because of the required time to upgrade it to newer versions we won't upgrade it.
> If you find bugs or want to discuss features not related to schema.org types but the methods provided by this package feel free to open an issue.
> If you need a newer version and want to upgrade this package: we will accept a PR which upgrades the generator to work with newer releases. 
> You can find more details in [#133](https://github.com/spatie/schema-org/issues/133). 
> We won't accept PRs adding single types/properties.
> 
> — **Tom Witkowski** [@Gummibeer](https://github.com/Gummibeer) @ _2020-07-24_

------

```php
use Spatie\SchemaOrg\Schema;

$localBusiness = Schema::localBusiness()
    ->name('Spatie')
    ->email('info@spatie.be')
    ->contactPoint(Schema::contactPoint()->areaServed('Worldwide'));

echo $localBusiness->toScript();
```

```html
<script type="application/ld+json">
{
    "@context": "http:\/\/schema.org",
    "@type": "LocalBusiness",
    "name": "Spatie",
    "email": "info@spatie.be",
    "contactPoint": {
        "@type": "ContactPoint",
        "areaServed": "Worldwide"
    }
}
</script>
```

## Support us

Learn how to create a package like this one, by watching our premium video course:

[![Laravel Package training](https://spatie.be/github/package-training.jpg)](https://laravelpackage.training)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/schema-org
```

## Usage

All types can be instantiated through the `Spatie\SchemaOrg\Schema` factory class, or with the `new` keyword.

``` php
$localBusiness = Schema::localBusiness()->name('Spatie');

// Is equivalent to:

$localBusiness = new LocalBusiness();
$localBusiness->name('Spatie');
```

> *All types also accept arrays of the expected data type, for example `sameAs` accepts a string or an array of strings.*

All types also implement the SPL's `ArrayAccess` for accessing the properties via array notation:

```php
$anotherLocalBusiness = new LocalBusiness();
var_dump(isset($anotherLocalBusiness['name'])); // => false
$anotherLocalBusiness['name'] = 'Spatie';
var_dump(isset($anotherLocalBusiness['name'])); // => true
var_dump($anotherLocalBusiness['name']); // => 'Spatie'
unset($anotherLocalBusiness['name']);
var_dump(isset($anotherLocalBusiness['name'])); // => false
```

Types can be converted to an array or rendered to a script.

```php
$localBusiness->toArray();

echo $localBusiness->toScript();

echo $localBusiness; // Same output as `toScript()`
```

Additionally, all types can be converted to a plain JSON string by just calling `json_encode()` with your object:

```php
echo json_encode($localBusiness);
```

I recommend double checking your structured data with [Google's structured data testing tool](https://search.google.com/structured-data/testing-tool).

### Enumerations

As of v1.6.0, all [Enumeration](http://schema.org/Enumeration) child types are available as classes with constants.

```php
Schema::book()->bookFormat(Spatie\SchemaOrg\BookFormatType::Hardcover);
```

There's no full API documentation for types and properties. You can refer to [the source](https://github.com/spatie/schema-org/tree/master/src) or to [the schema.org website](http://schema.org).

If you don't want to break the chain of a large schema object, you can use the `if` method to conditionally modify the schema.

```php
use Spatie\SchemaOrg\LocalBusiness;
use Spatie\SchemaOrg\Schema;

$business = ['name' => 'Spatie'];

$localBusiness = Schema::localBusiness()
    ->name($business['name'])
    ->if(isset($business['email']), function (LocalBusiness $schema) use ($business) {
        $schema->email($business['email']);
    });
```

### Identifier

As of v2.6.0 the `identifier` key is replaced by `@id`. This is due to the definition for the `ld+json` syntax.

> All schema.org syntaxes already have built-in representation for URIs and URLs, e.g. in Microdata 'itemid', in RDFa 1.1, 'resource', **in JSON-LD, '@id'.**
> 
> &mdash; [schema.org/docs](https://schema.org/docs/datamodel.html#identifierBg) // [PR#102](https://github.com/spatie/schema-org/pull/102) 

### Advanced Usage

If you'd need to set a custom property, you can use the `setProperty` method.

```php
$localBusiness->setProperty('foo', 'bar');
```

If you'd need to retrieve a property, you can use the `getProperty` method. You can optionally pass in a second parameter to provide a default value.

```php
$localBusiness->getProperty('name'); // 'Spatie'
$localBusiness->getProperty('bar'); // null
$localBusiness->getProperty('bar', 'baz'); // 'baz'
```

All properties can be retrieved as an array with the `getProperties` method.

```php
$localBusiness->getProperties(); // ['name' => 'Spatie', ...]
```

Multiple properties can be set at once using the `addProperties` method.

```php
$localBusiness->addProperties(['name' => 'value', 'foo' => 'bar']);
```

Context and type can be retrieved with the `getContext` and `getType` methods.

```php
$localBusiness->getContext(); // 'http://schema.org'
$localBusiness->getType(); // 'LocalBusiness'
```

### Graph - multiple items

The Graph has a lot of methods and utilities - the type-safe and simplest way is to use the overloaded methods of the `Spatie\SchemaOrg\Schema` class itself. These methods will get an already created or new instance of the requested schema.

```php
$graph = new Graph();

// Create a product and prelink organization
$graph
    ->product()
    ->name('My cool Product')
    ->brand($graph->organization());

// Hide the organization from the created script tag
$graph->hide(\Spatie\SchemaOrg\Organization::class);

// Somewhere else fill out the organization
$graph
    ->organization()
    ->name('My awesome Company');

// Render graph to script tag
echo $graph;
```

With these tools the graph is a collection of all available schemas, can link these schemas with each other and prevent helper schemas from being rendered in the script-tag.

### Graph Node identifiers

Sometimes you have to keep track of multiple Graph nodes of the same type - for example multiple `Person` nodes for different people in your Organization.
To do so you are able to use node identifiers on your graph instance.
If you don't provide an identifier a reserved keyword `default` identifier will be used.

```php
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Person;

$graph = new Graph();

// add a Person using chaining
$graph->person('freekmurze')
    ->givenName('Freek')
    ->familyName('Van der Herten')
    ->alternateName('freekmurze');

// add a Person using closure
$graph->person('sebastiandedeyne', function(Person $sebastian, Graph $graph): void {
    $sebastian
        ->givenName('Sebastian')
        ->familyName('De Deyne')
        ->alternateName('sebastiandedeyne');
}); 

// add a person using closure and second call with same identifier
$graph->person(
    'gummibeer', 
    fn(Person $gummibeer) => $gummibeer->alternateName('gummibeer')
);
$graph->person('gummibeer')
    ->givenName('Tom')
    ->familyName('Witkowski');

$graph->person('random')->name('Random Person');

// hide the random person from Graph
$graph->hide(Person::class, 'random');

echo json_encode($graph);
```

```json
{
    "@context":"https:\/\/schema.org",
    "@graph":[
        {
            "@type":"Person",
            "givenName":"Freek",
            "familyName":"Van der Herten",
            "alternateName":"freekmurze"
        },
        {
            "@type":"Person",
            "givenName":"Sebastian",
            "familyName":"De Deyne",
            "alternateName":"sebastiandedeyne"
        },
        {
            "@type":"Person",
            "alternateName":"gummibeer",
            "givenName":"Tom",
            "familyName":"Witkowski"
        }
    ]
}
```

## Known Issues

- The `Float` type isn't available since it's a reserved keyword in PHP
- The `Physician` type isn't available since it extends a type from the `health` extension spec

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [Tom Witkowski](https://github.com/Gummibeer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
