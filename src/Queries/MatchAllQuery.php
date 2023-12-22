<?php

declare(strict_types=1);

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Queries;

use stdClass;

final class MatchAllQuery implements Query
{
    private string $field;

    /** @var mixed[] */
    private array $values;

    /** @param mixed[] $values */
    public function __construct(string $field, array $values)
    {
        $this->field = $field;
        $this->values = $values;
    }

    /** @param mixed[] $values */
    public static function create(string $field, array $values = []): static
    {
        return new self($field, $values);
    }

    /** @return array<string, array<string, mixed[]>> */
    public function toArray(): array
    {
        $parameters = [
            'filters' => [
                $this->field => [
                    'match_all' => new stdClass(),
                ],
            ],
        ];

        if (!empty($this->field) && !empty($this->values)) {
            $parameters['match_all'][$this->field] = $this->values;
        }

        return $parameters;
    }
}
