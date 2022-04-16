<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['starting_time','ending_time'];

    public function table(){
        $this->belongsTo(Table::class);
    }
}
