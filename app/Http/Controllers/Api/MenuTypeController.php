<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuTypes;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class MenuTypeController extends Controller
{
    // nhat
    public function createMenuType(Request $request){
        try{
            $validateMenutype = Validator::make($request->all(), [
                'name_menu_type' => 'required|string|max:255',
                'stores_branch_id' => 'required|string|max:255',
            ]);
            if ($validateMenutype->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateMenutype->errors()
                ], 400);
            }
            $id_menu_type = Uuid::uuid4()->toString(). "-" . uniqid();
            $isExit = MenuTypes::where('name_menu_type', $request->name_menu_type)->where('stores_branch_id',$request->stores_branch_id)->first();
            if($isExit){
                return response()->json([
                    'status' => false,
                    'message' => 'Menu type already exists',
                ], 200);
            }
            $check = $menutype = MenuTypes::create([
                'id_menu_type' => $id_menu_type,
                'name_menu_type' => $request->name_menu_type,
                'stores_branch_id' => $request->stores_branch_id,
            ]);

            if(!$check){
                return response()->json([
                    'status' => false,
                    'message' => 'Create menu type failed',
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'Create menu type success',
                'data' => $check
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Create menu type failed',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
