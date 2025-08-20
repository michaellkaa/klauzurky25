<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Faculty;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'region',
        'role',
        'avatar_path',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        return '../../public/profpic_empty.png';
    }

    public function favorites()
{
    return $this->morphedByMany(Faculty::class, 'favoritable', 'favorites');
}

public function favoriteFaculties()
{
    return $this->morphedByMany(Faculty::class, 'favoritable', 'favorites');
}

public function favoriteUniversities()
{
    return $this->morphedByMany(University::class, 'favoritable', 'favorites');
}

public function events()
{
    return $this->hasMany(Event::class);
}

public function recommendedFields()
{
    return $this->belongsToMany(Field::class, 'user_recommended_fields', 'user_id', 'field_id')
                ->withTimestamps();
}


}
