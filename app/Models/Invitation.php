<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'quotes', 
        'male_name',
        'male_foto',
        'male_social_id',
        'male_father_name',
        'male_mother_name',
        'male_family_order',
        'female_name',
        'female_foto',
        'female_social_id',
        'female_father_name',
        'female_mother_name',
        'female_family_order',
        

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function gifts(){
        return $this->hasMany(Gift::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
}