<?php

namespace EdwinHoksberg\ElasticsearchQueryBuilder\Exceptions;

use InvalidArgumentException;

class BoolQueryTypeDoesNotExist extends InvalidArgumentException
{
    public static function forInvalidType(string $type): static
    {
        throw new self("Type '$type' for bool query does not exist. Please use one of the following: must, must_not, filter, should");
    }
}
