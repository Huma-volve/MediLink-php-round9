<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Models\Spelization;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
      public function spelizations()
    {
        $spelizations = Spelization::all();
        return response()->json($spelizations);
    }

}
