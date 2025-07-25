<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    public function items()
    {
        return $this->belongsTo(Items::class, 'item', 'id');
    }

    public function heads()
    {
        return $this->belongsTo(Head::class, 'head', 'id');
    }
}
