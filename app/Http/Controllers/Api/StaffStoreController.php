<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StaffStores;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use App\Models\Stores;
use App\Models\Floors;
use App\Models\StoreBranchs;
use App\Models\Tables;

class StaffStoreController extends Controller
{
    public function addStaff(Request $request)
    {
        try {
            //code...
            $uuid = Uuid::uuid4()->toString() . "-" . uniqid();
            $validate = Validator::make($request->all(), [
                'user_id' => 'required|string|max:255',
                'store_id' => 'required|string|max:255',
                // 'store_branch_id' => 'string|max:255',
                // roleID set input only 1 or 2
                // // 'roleID' => 'required|in:0,1,2,3',
                // 'employment_type' => 'required|in:Parttime,Fulltime',
                // 'status' => 'required|in:Active,Inactive',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->errors()
                ], 400);
            }

            $check_user = User::where('id', $request->user_id)->first();
            if (!$check_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 200);
            }

            $check_store_id = Stores::where('id_stores', $request->store_id)->first();
            if (!$check_store_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Store branch not found',
                ], 200);
            }
            $check_store_branch_id = StoreBranchs::where('id_store_branch', $request->store_branch_id)->first();

            $check_floor_id = Floors::where('id_floors', $request->floor_id)->first();

            $check_table_id = Tables::where('id_table', $request->table_id)->first();

            $staff_store = StaffStores::create([
                'id_staff_store' => $uuid,
                'user_id' => $request->user_id,
                'store_id' => $check_store_id->id,
                'store_branch_id' => $check_store_branch_id ? $check_store_branch_id->id : null,
                'floor_id' => $check_floor_id ? $check_floor_id->id : null,
                'table_id' => $check_table_id ? $check_table_id->id : null,
                'roleID' => $request->roleID ? $request->roleID : "2",
                'employment_type' => "Parttime",
                'image_id' => $request->image_id,
                'wage' => $request->wage,
                'description' => $request->description,
                'status' => "Active"
            ]);
            if (!$staff_store) {
                return response()->json([
                    'status' => false,
                    'message' => 'Add staff failed',
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'Add staff success',
                'data' => $staff_store
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Add staff failed',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
