<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlaceTypes;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PlaceTypeController extends Controller
{
    //nhat
   public function createPlaceType(Request $request){
    try{
        $validatePlacetype = Validator::make($request->all(), [
            'name_place_type' => 'required|string|max:255|unique:place_types',
        ]);
        if ($validatePlacetype->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validatePlacetype->errors()
            ], 400);
        }
        $uuid = Uuid::uuid4()->toString(). "-" . uniqid();
        $check = $placetype = PlaceTypes::create([
            'id_place_type' => $uuid,
            'name_place_type' => $request->name_place_type,
        ]);
        if(!$check){
            return response()->json([
                'status' => false,
                'message' => 'Create place type failed',
            ], 200);
        }
        return response()->json([
            'status' => true,
            'message' => 'Create place type success',
            'data' => $check
        ], 200);
    }catch(\Exception $e){
        return response()->json([
            'status' => false,
            'message' => 'Create place type failed',
            'errors' => $e->getMessage()
        ], 500);
    }
   }
}
