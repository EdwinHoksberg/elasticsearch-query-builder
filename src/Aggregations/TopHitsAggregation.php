<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\Sorts\Sort;

class TopHitsAggregation extends Aggregation
{
    protected int $size;

    protected ?Sort $sort = null;

    /** @var array<string, string|string[]> */
    private array $source = [];

    public static function create(string $name, int $size, ?Sort $sort = null): static
    {
        return new self($name, $size, $sort);
    }

    public function __construct(
        string $name,
        int $size,
        ?Sort $sort = null
    ) {
        $this->name = $name;
        $this->size = $size;
        $this->sort = $sort;
    }

    /** @param mixed[] $source */
    public function setSource(array $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function payload(): array
    {
        $parameters = [
            'size' => $this->size,
            '_source' => $this->source,
        ];

        if ($this->sort) {
            $parameters['sort'] = [$this->sort->toArray()];
        }

        return [
            'top_hits' => $parameters,
        ];
    }
}
