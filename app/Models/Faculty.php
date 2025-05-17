<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'description',
        'address',
        'website',
        'email',
        'phone',
        'application_link',
        'admission_notes',
        'open_day_date',
        'open_day_url',
        'exam_dates',
        'application_fee',
        'application_deadline',
        'bc_programs',
        'mgr_programs',
        'dr_programs',
        'logo_url',
        'banner_url',
    ];

    protected $casts = [
        'open_day_date' => 'date',
        'application_deadline' => 'date',
        'bc_programs' => 'array',
        'mgr_programs' => 'array',
        'dr_programs' => 'array',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
