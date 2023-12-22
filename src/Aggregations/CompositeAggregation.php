<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

final class CompositeAggregation extends Aggregation
{
    use WithAggregations;

    protected ?int $size = null;

    /** @var mixed[] */
    protected array $sources = [];

    public function __construct(
        string $name,
        Aggregation ...$aggregations
    ) {
        $this->name = $name;
        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public static function create(
        string $name,
        Aggregation ...$aggregations
    ): self {
        return new self($name, ...$aggregations);
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /** @param mixed[] $sources */
    public function setSources(array $sources): self
    {
        $this->sources = $sources;

        return $this;
    }

    /** @return mixed[] */
    public function payload(): array
    {
        $payload = [
            'composite' => ['sources' => $this->sources],
        ];

        if ($this->size !== null) {
            $payload['composite']['size'] = $this->size;
        }

        if (!$this->aggregations->isEmpty()) {
            $payload['aggs'] = $this->aggregations->toArray();
        }

        return $payload;
    }
}
