<?php

namespace Manivelle\Cache;

use Manivelle\Models\Screen;
use Closure;

class FilterTokens extends FilterValues
{
    protected $key = 'filters_tokens';
    protected $filterValuesKey = 'tokens';
}
