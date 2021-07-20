<?php namespace Manivelle\Models\Fields;

use Illuminate\Database\Eloquent\Model;
use DB;

class Field extends Model
{
    protected function getValueAttribute($value)
    {
        return null;
    }

    protected function getTokenAttribute($value)
    {
        return null;
    }

    public function scopeWhereField($query, $field)
    {
        $table = $this->getTable();
        $tablePivot = $table.'_morph_pivot';

        $query->whereExists(function ($query) use ($tablePivot, $table, $field) {
            $query->select(DB::raw(1))
                  ->from($tablePivot)
                  ->whereRaw($tablePivot.'.field_id = '.$table.'.id')
                  ->where($tablePivot.'.field_name', $field);
        });
    }

    public function scopeWhereNamespace($query, $namespace)
    {
        $query->where('namespace', $namespace);
    }

    public static function getTokens($field, $opts = [])
    {
        $query = self::query()
            ->where(DB::raw('1'), '1')
            ->whereField($field);

        if (isset($opts['namespace'])) {
            $query->whereNamespace($opts['namespace']);
        }

        $items = $query->get();

        $tokens = [];
        foreach ($items as $item) {
            $token = $item->token;
            if ($token !== null) {
                $tokens[] = $item->token;
            }
        }

        return $tokens;
    }

    public static function getValues($field, $opts = [])
    {
        $query = self::query()
            ->whereField($field);

        if (isset($opts['namespace'])) {
            $query->whereNamespace($opts['namespace']);
        }

        $items = $query->get();

        $values = [];
        foreach ($items as $item) {
            $value = $item->value;
            if ($value !== null) {
                $values[] = $item->value;
            }
        }

        return $values;
    }
}
