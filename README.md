# Build and execute ElasticSearch queries using a fluent PHP API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/edwinhoksberg/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/edwinhoksberg/elasticsearch-query-builder)
[![Tests](https://github.com/edwinhoksberg/elasticsearch-query-builder/actions/workflows/run-tests.yml/badge.svg)](https://github.com/edwinhoksberg/elasticsearch-query-builder/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/edwinhoksberg/elasticsearch-query-builder.svg?style=flat-square)](https://packagist.org/packages/edwinhoksberg/elasticsearch-query-builder)

---

This package is a _lightweight_ query builder for ElasticSearch. It was specifically built for our [elasticsearch-search-string-parser](https://github.com/spatie/elasticsearch-search-string-parser) so it covers most use-cases but might lack certain features. We're always open for PRs if you need anything specific!

```php
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\MaxAggregation;
use EdwinHoksberg\ElasticsearchQueryBuilder\Builder;
use EdwinHoksberg\ElasticsearchQueryBuilder\Queries\MatchQuery;

$client = Elastic\Elasticsearch\ClientBuilder::create()->build();

$companies = (new Builder($client))
    ->index('companies')
    ->addQuery(MatchQuery::create('name', 'spatie', fuzziness: 3))
    ->addAggregation(MaxAggregation::create('score'))
    ->search();
```

## Installation

You can install the package via composer:

```bash
composer require edwinhoksberg/elasticsearch-query-builder
```

## Basic usage

The only class you really need to interact with is the `EdwinHoksberg\ElasticsearchQueryBuilder\Builder` class. It requires an `\Elastic\Elasticsearch\Client` passed in the constructor. Take a look at the [ElasticSearch SDK docs](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/installation.html) to learn more about connecting to your ElasticSearch cluster. 

The `Builder` class contains some methods to [add queries](#adding-queries), [aggregations](#adding-aggregations), [sorts](#adding-sorts), [fields](#retrieve-specific-fields) and some extras for [pagination](#pagination). You can read more about these methods below. Once you've fully built-up the query you can use `$builder->search()` to execute the query or `$builder->getPayload()` to get the raw payload for ElasticSearch.

```php
use EdwinHoksberg\ElasticsearchQueryBuilder\Queries\RangeQuery;
use EdwinHoksberg\ElasticsearchQueryBuilder\Builder;

$client = Elastic\Elasticsearch\ClientBuilder::create()->build();

$builder = new Builder($client);

$builder->addQuery(RangeQuery::create('age')->gte(18));

$results = $builder->search(); // raw response from ElasticSearch
```

## Adding queries

The `$builder->addQuery()` method can be used to add any of the available `Query` types to the builder. The available query types can be found below or in the `src/Queries` directory of this repo. Every `Query` has a static `create()` method to pass its most important parameters.

## Adding sorts

The `Builder` (and some aggregations) has a `addSort()` method that takes a `Sort` instance to sort the results. You can read more about how sorting works in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html).

```php
use EdwinHoksberg\ElasticsearchQueryBuilder\Sorts\Sort;

$builder
    ->addSort(Sort::create('age', Sort::DESC))
    ->addSort(
        Sort::create('score', Sort::ASC)
            ->unmappedType('long')
            ->missing(0)
    );
```

## Retrieve specific fields

The `fields()` method can be used to request specific fields from the resulting documents without returning the entire `_source` entry. You can read more about the specifics of the fields parameter in [the ElasticSearch docs](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-fields.html).

```php
$builder->fields('user.id', 'http.*.status');
```

## Pagination

Finally the `Builder` also features a `size()` and `from()` method for the corresponding ElasticSearch search parameters. These can be used to build a paginated search. Take a look the following example to get a rough idea:

```php
use EdwinHoksberg\ElasticsearchQueryBuilder\Builder;

$pageSize = 100;
$pageNumber = $_GET['page'] ?? 1;

$pageResults = (new Builder(Elastic\Elasticsearch\ClientBuilder::create()))
    ->size($pageSize)
    ->from(($pageNumber - 1) * $pageSize)
    ->search();
```

## Testing

```bash
composer test
```

## Credits

- [Edwin Hoksberg](https://github.com/edwinhoksberg)
- [Alex Vanderbist](https://github.com/alexvanderbist)
- [Ruben Van Assche](https://github.com/rubenvanassche)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
