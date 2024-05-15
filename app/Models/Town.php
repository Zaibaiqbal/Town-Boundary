<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;
    protected $table = 'mun_code_town_code';


    public function getTownList()
    {
        return Town::get();
    }
}
