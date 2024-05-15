<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Town;

class TownController extends Controller
{

    public function getTownList()
    {
        $town = new Town;

        $town_list  = $town->getTownList();

        return response()->json($town_list,200);
    }
}
