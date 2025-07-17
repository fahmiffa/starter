<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{

    public function users()
    {
        return $this->hasMany(User::class, 'app', 'id');
    }
}
