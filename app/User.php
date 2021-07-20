<?php

namespace Manivelle;

use Panneau\User as BaseUser;
use Localizer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Manivelle\Models\Organisation;
use Manivelle\Models\Channel;
use Manivelle\Models\Playlist;
use Folklore\EloquentMediatheque\Traits\PicturableTrait;

use Manivelle\Models\Traits\LoadTrait;

class User extends BaseUser
{
    use LoadTrait, PicturableTrait, Authorizable;
    
    protected $visible = [
        'id',
        'type',
        'organisation_id',
        'name',
        'email',
        'locale',
        'organisation',
        'organisations',
        'channels',
        'playlists',
        'avatar'
    ];
    
    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'email',
        'locale'
    ];
    
    protected $hidden = [
        'pivot',
        'password'
    ];
    
    protected $appends = [
        'avatar'
    ];
    
    /**
     * Relations
     */
    public function organisation()
    {
        return $this->belongsTo('Manivelle\Models\Organisation', 'organisation_id');
    }
    
    public function organisations()
    {
        return $this->hasMany(\Manivelle\Models\UserOrganisation::class, 'user_id');
    }
    
    public function channels()
    {
        return $this->hasMany(\Manivelle\Models\UserChannel::class, 'user_id');
    }
    
    public function playlists()
    {
        return $this->hasMany(\Manivelle\Models\UserPlaylist::class, 'user_id');
    }
    
    public function syncOrganisations($organisations)
    {
    }
    
    /**
     * Load relations
     */
    public function loadOrganisation()
    {
        $this->loadIfNotLoaded([
            'organisation'
        ]);
        
        return $this;
    }
    
    public function loadOrganisations()
    {
        $this->loadIfNotLoaded([
            'organisations',
            'organisations.organisation'
        ]);
        
        return $this;
    }
    
    public function loadPictures()
    {
        $this->loadIfNotLoaded([
            'pictures'
        ]);
        
        return $this;
    }
    
    /**
     * Accessors and mutators
     */
    public function getAvatarAttribute()
    {
        if (sizeof($this->pictures)) {
            return $this->pictures[0];
        }
        
        return null;
    }

    /**
     * Returns the user's locale, or the fallback locale
     * if none defined (config locale.locale)
     *
     * @param string $locale The locale in the DB
     * @return string
     */
    public function getLocaleAttribute($locale)
    {
        return Localizer::validateLocale($locale);
    }

    /**
     * Before setting the locale check that the locale is valid
     *
     * @param string $locale
     */
    public function setLocaleAttribute($locale)
    {
        $this->attributes['locale'] = Localizer::validateLocale($locale);
    }
}
