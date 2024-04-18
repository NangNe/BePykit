<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\StoreBranchs;
use App\Models\StaffStores;
use App\Models\Stores;
use Ramsey\Uuid\Uuid;
use App\Models\PlaceTypes;
use App\Models\Addresses;
use PhpParser\Node\Stmt\TryCatch;

class StoreBranchController extends Controller
{
    //
    public function getBranchs(Request $request){
       try {
        //code...
        $validateUser = Validator::make(
            $request->all(),
            [
                'store_id' => 'required',
                'user_id' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validate error',
                'error' => $validateUser->errors()
            ], 200);
        }

        $store= Stores::where('id_stores', $request->store_id)->first();
        if(!$store){
            return response()->json([
                'status' => false,
                'message' => 'store not found',
                'error' => 'store not found'
            ], 200);
        }
        
        $staffStore = StaffStores::where('user_id', $request->user_id)->where('store_id', $store->id)->first();
        if(!$staffStore){
            return response()->json([
                'status' => false,
                'message' => 'branch not found',
                'error' => 'branch not found'
            ], 200);
        }
        
        $branchs = $staffStore->roleID=="0" ? StoreBranchs::where('store_id', $store->id)->get() : StoreBranchs::where('store_id', $store->id)->where('id', $staffStore->store_branch_id)->get();

        if(count($branchs) == 0){
            return response()->json([
                'status' => false,
                'message' => 'branch not found',
                'error' => 'branch not found'
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'branchs' => $branchs,
        ], 200);

       } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            'status' => false,
            'message' => 'error',
            'error' => $th->getMessage()
        ], 200);
       }
        
    }

    public function addStoreBranch(Request $request){
        try {
            //code...
            $uuid = Uuid::uuid4()->toString() . "-" . uniqid();
            $validator = Validator::make(
                $request->all(),
                [
                    'street' => 'required',
                    'country' => 'required',
                    'postal_code' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'store_id' => 'required',
                    'place_type_id' => 'required',
                    // 'address_id' => 'required',
                    'name_store_branch' => 'required',
                    'open_time' => 'required',
                    'close_time' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validator->errors()
                ], 200);
            }
            $addAddress = Addresses::create([
                'id_address' => $uuid,
                'street' => $request->street,
                'village' => $request->village,
                'commune' => $request->commune,
                'district' => $request->district,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
           
            if(!$addAddress){
                return response()->json([
                    'status' => false,
                    'message' => 'add address error',
                    'error' => 'add address error'
                ], 200);
            }
            
            $addAddress -> save();
            $id_stores = Stores::where('id_stores', $request->store_id)->first()->id;
            
            if(!$id_stores){
                return response()->json([
                    'status' => false,
                    'message' => 'store not found',
                    'error' => 'store not found'
                ], 200);
            }

            $id_place_types = PlaceTypes::where('id_place_type', $request->place_type_id)->first()->id;
            if(!$id_place_types){
                return response()->json([
                    'status' => false,
                    'message' => 'place type not found',
                    'error' => 'place type not found'
                ], 200);
            }

            
           // get id in table address
            $id_address = Addresses::where('id_address', $uuid)->first()->id;
        

            $storebranch = new StoreBranchs();
            $storebranch->id_store_branch = $uuid;
            $storebranch->store_id = $id_stores;
            $storebranch->place_type_id = $id_place_types;
            $storebranch->address_id = $id_address;
            $storebranch->image_id = $request->image_id;
            $storebranch->name_store_branch = $request->name_store_branch;
            $storebranch->description = $request->description;
            $storebranch->phone_number = $request->phone_number;
            $storebranch->status ="Active";
            $storebranch->open_time = $request->open_time;
            $storebranch->close_time = $request->close_time;
            $storebranch->save();
           
            return response()->json([
                'status' => true,
                'message' => 'add store branch success',
            ], 200);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'error',
                'error' => $th->getMessage()
            ], 200);
        }
    }
}
