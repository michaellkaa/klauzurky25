<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'location',
        'website',
        'email',
        'phone',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'about',
        'type',
        'field',
        'language',
        'logo_url',
        'banner_url',
    ];

       public function favoritedByUsers()
{
    return $this->morphToMany(User::class, 'favoritable', 'favorites');
}

}
