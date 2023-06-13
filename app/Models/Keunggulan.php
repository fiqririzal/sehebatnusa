<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keunggulan extends Model
{
    use HasFactory;
    protected $table = "keunggulan";

    protected $fillable =
    [
        'judul','deskripsi','gambar', 'created_at', 'updated_at'
    ];
}
