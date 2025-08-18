<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    public function question() {
        return $this->belongsTo(Question::class);
    }
    public function field() {
        return $this->belongsTo(Field::class);
    }
    public function fields()
    {
        return $this->belongsToMany(Field::class, 'answer_field_points')
                    ->withPivot('points');
    }
}
