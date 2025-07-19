<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    protected $table   = 'head';
    protected $appends = ['kode'];

    public function cart()
    {
        return $this->hasMany(Cart::class, 'head', 'id');
    }

    public function getkodeAttribute()
    {
        $nom = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        return 'P'.$nom.date("ymd", strtotime($this->tanggal));
    }

}
