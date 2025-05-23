<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
     use HasFactory;

     protected $fillable = ['nombre', 'is_deleted'];

    public function pelicula()
    {
        return $this->hasMany(Pelicula::class);
    }
}
