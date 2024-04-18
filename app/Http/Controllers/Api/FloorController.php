<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\StoreBranchs;
use App\Models\Floors;
use App\Models\Images;

class FloorController extends Controller
{
    //
    public function getFloors(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'store_id' => 'required',
                    'store_branch_id' => 'required',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 400);
            }
            $storeId = Stores::where('id_stores', $request->store_id)->first()->id;
            $storeBranchId = StoreBranchs::where('id_store_branch', $request->store_branch_id)
                ->where('store_id', $storeId)->first()->id;
            $floors = Floors::where('store_id', $storeId)
                ->where('store_branch_id', $storeBranchId)->get();

            if (count($floors) > 0) {
            foreach ($floors as $floor) {
                    $floor->images = Images::where('image_query_id', $floor->id_floors)->where('image_type', 'floor')->get();
                    $floor->tables = $floor->tables;
                }
            }

            foreach ($floors as $floor) {
                foreach ($floor->tables as $table) {
                    $table->stores_branch_id=$request->store_branch_id;
                    $table->floor_id=$floor->id_floors;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'get floors success',
                'floors' => $floors
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'floors' => []
            ], 200);
        }
    }
}