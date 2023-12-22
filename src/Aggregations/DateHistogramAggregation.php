<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations;

use EdwinHoksberg\ElasticsearchQueryBuilder\AggregationCollection;
use EdwinHoksberg\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

final class DateHistogramAggregation extends Aggregation
{
    use WithAggregations;

    protected string $field = '';
    protected string $script = '';
    protected int|string $interval = '';
    protected int|string $fixedInterval = '';
    protected string $format = '';
    protected int|null $minDocCount = null;

    /** @var array<string, int> */
    protected ?array $extendedBounds = null;

    protected string $timezone = '';

    /** @var mixed[]|null */
    protected ?array $order = null;

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

    public function field(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function script(string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function interval(int|string $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function fixedInterval(int|string $fixedInterval): self
    {
        $this->fixedInterval = $fixedInterval;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /** @param int[] $extendedBounds */
    public function extendedBounds(?array $extendedBounds): self
    {
        $this->extendedBounds = $extendedBounds;

        return $this;
    }

    public function timezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /** @param mixed[]|null $order */
    public function order(?array $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function minDocCount(int $minDocCount): self
    {
        $this->minDocCount = $minDocCount;

        return $this;
    }

    /** @return mixed[] */
    public function payload(): array
    {
        $aggregation = [
            'field' => $this->field,
            'script' => $this->script,
            'interval' => $this->interval,
            'fixed_interval' => $this->fixedInterval,
            'format' => $this->format,
            'extended_bounds' => $this->extendedBounds,
            'time_zone' => $this->timezone,
            'order' => $this->order,
        ];

        if ($this->minDocCount !== null) {
            $aggregation['min_doc_count'] = $this->minDocCount;
        }

        $data = [
            'date_histogram' => array_filter($aggregation),
        ];

        if (!$this->aggregations->isEmpty()) {
            $data['aggs'] = $this->aggregations->toArray();
        }

        return $data;
    }
}
