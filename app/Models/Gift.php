<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = ['invitation_id', 'type', 'name', 'bank_account', 'qrcode'];

    public function invitation(){
        return $this->belongsTo(Invitation::class);
    }
}