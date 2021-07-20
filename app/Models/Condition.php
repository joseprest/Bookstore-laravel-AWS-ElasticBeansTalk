<?php namespace Manivelle\Models;

use Manivelle\User;

use Illuminate\Database\Eloquent\Model;

use Panneau\Support\Traits\HasFields;
use Panneau\Support\Traits\HasFieldsSnippet;

use Folklore\EloquentMediatheque\Traits\MetadatableTrait;
use Folklore\EloquentMediatheque\Traits\WritableTrait;

use Carbon\Carbon;

use Manivelle\Models\Traits\LoadTrait;

class Condition extends Model
{
    use LoadTrait, HasFields, HasFieldsSnippet, MetadatableTrait, WritableTrait;

    protected $table = 'conditions';
    
    protected $fillable = [
        'name'
    ];
    
    protected $appends = [
        'fields',
        'snippet'
    ];
    
    protected $with = [
        'metadatas',
        'texts'
    ];
    
    protected $conditionType;
    protected $cachedFields = null;
    
    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($model) {
            foreach ($model->playlists as $playlist) {
                $playlist->touch();
            }
        });
    }
    
    /**
     * Relationships
     */
    public function organisation()
    {
        return $this->belongsTo(\Manivelle\Models\Organisation::class, 'organisation_id');
    }
    
    public function playlists()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Playlist::class,
            'playlists_bubbles_pivot',
            'condition_id',
            'playlist_id'
        )
        ->withPivot('id', 'order', 'settings', 'bubble_id')
        ->withTimestamps();
    }
    
    /**
     * Fields and snippet
     */
    protected function getConditionType()
    {
        if ($this->conditionType) {
            return $this->conditionType;
        }

        $conditionType = app('\Manivelle\Support\ConditionType');
        $this->conditionType = $conditionType->withModel($this);
        return $this->conditionType;
    }
    
    protected function getFieldsCollection()
    {
        return $this->getConditionType()->getFields();
    }
    
    protected function getFieldsSnippet()
    {
        return $this->getConditionType()->getSnippet();
    }
    
    /**
     * Accessors and mutators
     */
    protected function getDescriptionAttribute()
    {
        $fields = $this->fields;
        $line = [];
        
        //Days
        if ($fields->days && sizeof($fields->days)) {
            $days = $this->getDaysFromFields($fields);
            
            if (sizeof($days)) {
                $lastDay = $days[sizeof($days)-1];
                $daysList = [];
                if (sizeof($days) > 1) {
                    $daysList[] = implode(', ', array_slice($days, 0, -1));
                }
                $daysList[] = $lastDay;
                $line[] = 'Seulement le '.implode(' et ', $daysList);
            }
        }
        
        //Daterange
        if ($fields->daterange && sizeof($fields->daterange)) {
            $daterange = $this->getDateRangeFromFields($fields);
            $start = $daterange['start'];
            $end = $daterange['end'];
            
            if ($start && !$end) {
                $line[] = 'Après le '.$start->formatLocalized('%d %B');
            } elseif ($end && !$start) {
                $line[] = 'Avant le '.$end->formatLocalized('%d %B');
            } elseif ($start && $end) {
                $line[] = 'Entre le '.$start->formatLocalized('%d %B').' et le '.$end->formatLocalized('%d %B');
            }
        }
        
        //Time
        if ($fields->time && sizeof($fields->time)) {
            $time = $this->getTimeFromFields($fields);
            $startTime = $time['start'];
            $endTime = $time['end'];
            
            if ($startTime && !$endTime) {
                $line[] = 'Après '.$startTime->format('G\hi');
            } elseif ($endTime && !$startTime) {
                $line[] = 'Avant '.$endTime->format('G\hi');
            } elseif ($startTime && $endTime) {
                $line[] = 'Entre '.$startTime->format('G\hi').' et '.$endTime->format('G\hi');
            }
        }
        
        return implode("\n", $line);
    }
    
    /**
     * Check if date is valid within this condition
     *
     * @param  Carbon\Carbon  $date The date to check
     * @return boolean       Whether the date is valid or not
     */
    public function isValidWithDate(Carbon $date)
    {
        $isValid = true;
    
        if (!$this->cachedFields) {
            $this->cachedFields = $this->fields;
        }
        
        if ($this->checkDays($this->cachedFields, $date) === false) {
            $isValid = false;
        }
        
        if ($this->checkDateRange($this->cachedFields, $date) === false) {
            $isValid = false;
        }
        
        if ($this->checkDate($this->cachedFields, $date) === false) {
            $isValid = false;
        }
        
        if ($this->checkTime($this->cachedFields, $date) === false) {
            $isValid = false;
        }
        
        return $isValid;
    }
    
    /**
     * Get the priority of this condition on a particular date
     *
     * @param  Carbon\Carbon $date The date to get the priority
     * @return integer The priority of this condition
     */
    public function getPriority(Carbon $date)
    {
        if (!$this->cachedFields) {
            $this->cachedFields = $this->fields;
        }
        
        $priority = 0;
        
        if ($this->checkDays($this->cachedFields, $date)) {
            $priority++;
        }
        
        if ($this->checkDateRange($this->cachedFields, $date)) {
            $priority++;
        }
        
        if ($this->checkDate($this->cachedFields, $date)) {
            $priority++;
        }
        
        if ($this->checkTime($this->cachedFields, $date)) {
            $priority++;
        }
        
        return $priority;
    }
    
    /**
     * Get the days of the weeks used in this condition
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @return array The days of the weeks
     */
    protected function getDaysFromFields($fields)
    {
        $daysIndex = $fields->days->toArray();
        sort($daysIndex);
        $days = [];
        $daysNames = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        foreach ($daysIndex as $index) {
            $days[] = $daysNames[(int)$index];
        }
        return $days;
    }
    
    /**
     * Get the date range used in this condition
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @return array The start and end date
     */
    protected function getDateRangeFromFields($fields)
    {
        $startParts = explode('-', array_get($fields->daterange, '0', ''));
        $endParts = explode('-', array_get($fields->daterange, '1', ''));
        
        $startDate = $endDate = null;
        if (sizeof($startParts) === 3) {
            $startDate = Carbon::createFromDate($startParts[0], $startParts[1], $startParts[2]);
        }
        if (sizeof($endParts) === 3) {
            $endDate = Carbon::createFromDate($endParts[0], $endParts[1], $endParts[2]);
        }
        
        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }
    
    /**
     * Get the time range used in this condition
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @return array The start and end time
     */
    protected function getTimeFromFields($fields)
    {
        $startParts = explode(':', array_get($fields->time, '0', ''));
        $endParts = explode(':', array_get($fields->time, '1', ''));
        
        $startTime = $endTime = null;
        if (sizeof($startParts) === 3) {
            $startTime = Carbon::createFromTime($startParts[0], $startParts[1], $startParts[2]);
        }
        if (sizeof($endParts) === 3) {
            $endTime = Carbon::createFromTime($endParts[0], $endParts[1], $endParts[2]);
        }
        
        return [
            'start' => $startTime,
            'end' => $endTime
        ];
    }
    
    /**
     * Check if the date is valid against condition days
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @param  Carbon $date The date to check
     * @return boolean Whether the date are valid of not
     */
    protected function checkDays($fields, Carbon $date)
    {
        if (!isset($fields->days) || !sizeof($fields->days)) {
            return null;
        }
        
        $weekday = (int)$date->format('N');
        
        $found = false;
        foreach ($fields->days as $day) {
            $day = (int)$day+1;
            if ($day === $weekday) {
                $found = true;
            }
        }
        
        return $found;
    }
    
    /**
     * Check if the date is valid against condition date range
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @param  Carbon $date The date to check
     * @return boolean Whether the date is valid of not
     */
    protected function checkDateRange($fields, Carbon $date)
    {
        if (!isset($fields->daterange) || empty($fields->daterange)) {
            return null;
        }
        
        $start = array_get($fields->daterange, '0', null);
        $end = array_get($fields->daterange, '1', null);
        $startDate = $endDate = null;
        if (!empty($start)) {
            $startDate = Carbon::parse($start);
        }
        if (!empty($end)) {
            $endDate = Carbon::parse($end);
        }
        
        if (!$startDate && !$endDate) {
            return null;
        }
        
        if ($startDate && !$endDate) {
            return $date->gt($startDate);
        } elseif ($endDate && !$startDate) {
            return $date->lt($endDate);
        }
        
        return $date->between($startDate, $endDate);
    }
    
    /**
     * Check if the date is valid against condition date
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @param  Carbon $date The date to check
     * @return boolean Whether the date is valid of not
     */
    protected function checkDate($fields, Carbon $date)
    {
        if (!isset($fields->date) || empty($fields->date)) {
            return null;
        }
        
        $conditionDate = Carbon::parse($fields->date);
        
        return $conditionDate->toDateString() === $date->toDateString();
    }
    
    /**
     * Check if the date is valid against condition time
     *
     * @param  Illuminate\Support\Fluent $fields The model fields
     * @param  Carbon $date The date to check
     * @return boolean Whether the date is valid of not
     */
    protected function checkTime($fields, Carbon $date)
    {
        if (!isset($fields->time) || empty($fields->time)) {
            return null;
        }
        
        $start = array_get($fields->time, '0', null);
        $end = array_get($fields->time, '1', null);
        $startDate = $endDate = null;
        $dateString = $date->toDateString();
        if (!empty($start)) {
            $startDate = Carbon::parse($dateString.' '.$start);
        }
        if (!empty($end)) {
            $endDate = Carbon::parse($dateString.' '.$end);
        }
        
        if (!$startDate && !$endDate) {
            return null;
        }
        
        if ($startDate && !$endDate) {
            return $date->gt($startDate);
        } elseif ($endDate && !$startDate) {
            return $date->lt($endDate);
        }
        
        return $date->between($startDate, $endDate);
    }
}
