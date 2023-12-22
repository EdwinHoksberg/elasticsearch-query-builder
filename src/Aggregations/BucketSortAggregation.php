<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\Sorts\Sort;

final class BucketSortAggregation extends Aggregation
{
    protected Sort $sort;

    public static function create(string $name, Sort $sort): self
    {
        return new self($name, $sort);
    }

    public function __construct(string $name, Sort $sort)
    {
        $this->name = $name;
        $this->sort = $sort;
    }

    /** @return array<string, array<string, string[][]>> */
    public function payload(): array
    {
        return [
            'bucket_sort' => [
                'sort' => $this->sort->toArray(),
            ],
        ];
    }
}
