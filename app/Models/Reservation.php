<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name','table_id','starting_time','ending_time'];
    protected $appends = ['table_number'];

    public function table(){
       return $this->belongsTo(Table::class);
    }
    public function getTableNumberAttribute(){
       return $this->table()->first()->number;
    }
    
}
