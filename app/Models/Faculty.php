<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'university',
        'name',
        'description',
        'address',
        'website',
        'email',
        'phone',
        'application_link',
        'admission_notes',
        'open_day_dates',
        'open_day_url',
        'exam_dates',
        'application_fee',
        'application_deadlines',
        'bc_programs',
        'mgr_programs',
        'dr_programs',
        'logo_url',
        'banner_url',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'fields_of_study',

    ];
    
   public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }


    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
