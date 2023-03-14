<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['invitation_id', 'title', 'address', 'landmark', 'date', 'start_time', 'end_time', 'status'];

    public function invitation(){
        return $this->belongsTo(Invitation::class);
    }
}