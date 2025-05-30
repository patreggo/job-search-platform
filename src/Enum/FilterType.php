<?php

namespace App\Enum;

enum FilterType: string
{
    case EXACT = 'exact';
    case LIKE = 'like';
    case RANGE = 'range';
    case IN = 'in';
    case BOOLEAN = 'boolean';
    case DATE_RANGE = 'date_range';
    case GREATER_THAN = 'gt';
    case LESS_THAN = 'lt';
    case GREATER_THAN_OR_EQUAL = 'gte';
    case LESS_THAN_OR_EQUAL = 'lte';
}