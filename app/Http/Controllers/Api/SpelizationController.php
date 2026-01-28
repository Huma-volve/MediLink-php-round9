<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Spelization;
use Illuminate\Http\Request;

class SpelizationController extends Controller
{
      public function show()
    {
        $spelizations = Spelization::all();
        return response()->json($spelizations);
    }

}
