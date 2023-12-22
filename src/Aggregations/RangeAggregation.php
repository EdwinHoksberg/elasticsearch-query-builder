<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

final class RangeAggregation extends Aggregation
{
    use WithAggregations;

    protected string $field;

    /** @var array<string, string> */
    protected array $ranges;

    public function __construct(
        string $name,
        string $field,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->field = $field;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public static function create(
        string $name,
        string $field,
        Aggregation ...$aggregations
    ): self {
        return new self($name, $field, ...$aggregations);
    }

    /** @param array<string, string> $ranges */
    public function setRanges(array $ranges): self
    {
        $this->ranges = $ranges;

        return $this;
    }

    /** @return array<string, array<string, mixed>> */
    public function payload(): array
    {
        $parameters = [
            'range' => [
                'field' => $this->field,
                'ranges' => $this->ranges,
            ],
        ];

        if (!empty($this->aggregations)) {
            $parameters['aggs'] = $this->aggregations->toArray();
        }

        return $parameters;
    }
}
