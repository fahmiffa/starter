<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{

    public function items()
    {
        return $this->hasMany(Items::class, 'id', 'item');
    }
}
