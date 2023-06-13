<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = "lembaga";
    protected $fillable =[
        'nama' ,'logo' , 'created_at', 'updated_at'
    ];

}