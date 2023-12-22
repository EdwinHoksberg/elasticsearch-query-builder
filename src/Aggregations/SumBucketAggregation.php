<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

final class SumBucketAggregation extends Aggregation
{
    use WithAggregations;

    protected string $bucketsPath;

    public static function create(string $name, string $bucketsPath, Aggregation ...$aggregations): self
    {
        return new self($name, $bucketsPath, ...$aggregations);
    }

    public function __construct(string $name, string $bucketsPath, Aggregation ...$aggregations)
    {
        $this->name = $name;
        $this->bucketsPath = $bucketsPath;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    /** @return array<string, array<string, string>> */
    public function payload(): array
    {
        return [
            'sum_bucket' => ['buckets_path' => $this->bucketsPath],
        ];
    }
}
