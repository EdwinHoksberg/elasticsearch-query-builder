<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

final class BucketSelectorAggregation extends Aggregation
{
    use WithAggregations;

    /** @var array<string, string> */
    protected array $bucketsPath;

    protected string $script;

    /** @param array<string, string> $bucketsPath */
    public function __construct(
        string $name,
        array $bucketsPath,
        string $script,
        Aggregation ...$aggregations,
    ) {
        $this->name = $name;
        $this->bucketsPath = $bucketsPath;
        $this->script = $script;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    /** @param array<string, string> $bucketsPath */
    public static function create(
        string $name,
        array $bucketsPath,
        string $script,
        Aggregation ...$aggregations,
    ): self {
        return new self($name, $bucketsPath, $script, ...$aggregations);
    }

    /** @return array<string, array<string, array<string, string>|string>> */
    public function payload(): array
    {
        return [
            'bucket_selector' => [
                'buckets_path' => $this->bucketsPath,
                'script' => $this->script,
            ],
        ];
    }
}
