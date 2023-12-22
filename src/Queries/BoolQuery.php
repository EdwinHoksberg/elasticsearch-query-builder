<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Queries;

use EdwinHoksberg\ElasticsearchQueryBuilder\Exceptions\BoolQueryTypeDoesNotExist;

class BoolQuery implements Query
{
    protected array $must = [];
    protected array $filter = [];
    protected array $should = [];
    protected array $must_not = [];
    protected ?int $minimumShouldMatch = null;

    public static function create(): static
    {
        return new self();
    }

    public function add(Query $query, string $type = 'must'): static
    {
        if (!in_array($type, ['must', 'filter', 'should', 'must_not'])) {
            throw BoolQueryTypeDoesNotExist::forInvalidType($type);
        }

        $this->$type[] = $query;

        return $this;
    }

    public function minimumShouldMatch(?int $minimumShouldMatch): self
    {
        $this->minimumShouldMatch = $minimumShouldMatch;

        return $this;
    }

    public function toArray(): array
    {
        $bool = [
            'must' => array_map(static fn (Query $query) => $query->toArray(), $this->must),
            'filter' => array_map(static fn (Query $query) => $query->toArray(), $this->filter),
            'should' => array_map(static fn (Query $query) => $query->toArray(), $this->should),
            'must_not' => array_map(static fn (Query $query) => $query->toArray(), $this->must_not),
        ];

        if ($this->minimumShouldMatch) {
            $bool['minimum_should_match'] = $this->minimumShouldMatch;
        }

        return [
            'bool' => array_filter($bool),
        ];
    }
}
