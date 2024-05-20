<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Town;
use App\Http\Resources\TownResource;

class TownController extends Controller
{
    public function __construct()
    {
        $this->data     = NULL;
        $this->response = 422;
    }

    public function getTownList()
    {
        $town = new Town;
        $town_list = $town->getTownList();
        return response()->json($town_list, 200);
    }

    public function getTownsNameList()
    {
        $town = new Town;
        $town_list = $town->getTownList();
        $this->data['town_name_list'] = TownResource::collection($town_list);
        return response()->json($this->data, 200);
    }

    public function getTownBoundaries()
    {
        try {
            // Fetch town boundaries from the database
            $town = new Town;
            $townBoundaries = $town->getTownList();

            // Send the town boundaries as JSON response
            return response()->json($townBoundaries, 200);
        } catch (\Exception $e) {
            // Handle errors
            \Log::error('Error executing SQL query: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
