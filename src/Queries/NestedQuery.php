<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Queries;

class NestedQuery implements Query
{
    protected string $path;
    protected Query $query;
    protected ?string $scoreMode = null;

    /** @var ?mixed[] */
    protected ?array $innerHits = null;

    public static function create(string $path, Query $query): self
    {
        return new self($path, $query);
    }

    public function __construct(string $path, Query $query)
    {
        $this->path = $path;
        $this->query = $query;
    }

    public function scoreMode(?string $scoreMode): self
    {
        $this->scoreMode = $scoreMode;

        return $this;
    }

    /** @param mixed[] $innerHits */
    public function innerHits(array $innerHits = []): self
    {
        $this->innerHits = array_merge(['highlight' => ['fields' => []], 'size' => 100], $innerHits);

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'nested' => [
                'path' => $this->path,
                'query' => $this->query->toArray(),
            ],
        ];

        if ($this->scoreMode !== null) {
            $data['nested']['score_mode'] = $this->scoreMode;
        }

        if ($this->innerHits !== null) {
            $data['nested']['inner_hits'] = $this->innerHits;
        }

        return $data;
    }
}
