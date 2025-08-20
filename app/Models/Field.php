<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    use HasFactory;

    protected $fillable = ['name'];

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function usersRecommended()
    {
        return $this->belongsToMany(User::class, 'user_recommended_fields');
    }

}
