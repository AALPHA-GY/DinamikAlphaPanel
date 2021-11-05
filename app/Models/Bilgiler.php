<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilgiler extends Model
{
    use HasFactory;
    protected $table="bilgiler";
    protected $fillable=["id","kolonid","baslik","slug","sirano","metin","created_at","updated_at"];

    public function kolonBilgisi()
    {
      return $this->hasMany(\App\Models\Kolonlar::class,"id","kolonid");
    }
}
