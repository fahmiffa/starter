<?php
namespace App\Models;
use App\Models\cart;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{

    public function getstoksAttribute()
    {
        $cart = Cart::where('item',$this->id)->sum('count');
        return $this->stok - $cart;
    }

    public function kategori()
    {
        return $this->belongsTo(Categori::class, 'cat', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }
}
