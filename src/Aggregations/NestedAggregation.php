<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class NestedAggregation extends Aggregation
{
    use WithAggregations;

    protected string $path;

    public static function create(string $name, string $path, Aggregation ...$aggregations): self
    {
        return new self($name, $path, ...$aggregations);
    }

    public function __construct(string $name, string $path, Aggregation ...$aggregations)
    {
        $this->name = $name;
        $this->path = $path;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function payload(): array
    {
        $payload = [
            'nested' => [
                'path' => $this->path,
            ],
        ];

        if (!$this->aggregations->isEmpty()) {
            $payload['aggs'] = $this->aggregations->toArray();
        }

        return $payload;
    }
}
