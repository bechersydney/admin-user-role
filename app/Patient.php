<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['first_name', 'last_name',''];
    public function accounts(){
        return $this->hasMany(Account::class);
    }
}
