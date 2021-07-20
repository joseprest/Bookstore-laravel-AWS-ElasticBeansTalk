<?php namespace Manivelle\Panneau\Resources;

use Panneau\Support\EloquentResource;

class SourcesResource extends EloquentResource
{
    protected $modelName = \Manivelle\Models\Source::class;
    
    protected function scopeHandle($query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('handle', $value);
        } else {
            $query->where('handle', $value);
        }
    }
}
