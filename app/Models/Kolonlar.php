<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kolonlar extends Model
{
    use HasFactory;
    protected $table="kolonlar";
    protected $fillable=["id","baslik","slug","sirano","created_at","updated_at"];
}
