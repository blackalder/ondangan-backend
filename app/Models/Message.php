<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['invitation_id', 'name', 'email', 'message'];

    public function invitation(){
        return $this->belongsTo(Invitation::class);
    }
}