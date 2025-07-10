<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{

    public function kategori()
    {
        return $this->belongsTo(Categori::class, 'cat', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }
}
