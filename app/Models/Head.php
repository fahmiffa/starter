<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    protected $table = 'head';
    
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'head', 'id');
    }
}
