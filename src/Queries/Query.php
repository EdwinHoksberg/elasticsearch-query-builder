<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Queries;

interface Query
{
    public function toArray(): array;
}
