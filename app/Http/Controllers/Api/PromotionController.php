<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
   
    public function index()
    {
        $promotions = Promotion::with('category')->get();
        return response()->json(["promotions" => $promotions]);
    }
}
