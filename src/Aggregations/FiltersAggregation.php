<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\FilterAggregation as BaseFilterAggregation;
use EdwinHoksberg\ElasticsearchQueryBuilder\Queries\Query;

final class FiltersAggregation extends BaseFilterAggregation
{
    public static function create(
        string $name,
        Query $filter,
        Aggregation ...$aggregations
    ): self {
        return new self($name, $filter, ...$aggregations);
    }

    /** @return array<string, mixed[][]> */
    public function payload(): array
    {
        $parameters = [
            'filters' => $this->filter->toArray(),
        ];

        if (!empty($this->aggregations)) {
            $parameters['aggs'] = $this->aggregations->toArray();
        }

        return $parameters;
    }
}
