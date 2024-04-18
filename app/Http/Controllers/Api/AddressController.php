<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\Addresses;
use Illuminate\Support\Facades\Validator;
class AddressController extends Controller
{
    public function createAddress(Request $request)
    {
     try {
        
        $validateAddress = Validator::make(
            $request->all(),
            [              ]
            );
            if ($validateAddress->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateAddress->errors()
                ], 200);
            }
            $uuid = Uuid::uuid4()->toString();
            $check = $address = Addresses::create([
                'id_address' => $uuid,
                'street' => $request->street ?? null,
                'village' => $request->village ?? null,
                'commune' => $request->commune ?? null,
                'district' => $request->district ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state ?? null,
                'country' => $request->country ?? null,
                'postal_code' => $request->postal_code ?? null,
                'latitude' => $request->latitude ?? null,
                'longitude' => $request->longitude ?? null,

            ]); 

            if(!$check){
                return response()->json([
                    'status' => false,
                    'message' => 'create address fail',
                    'error' => 'create address fail'
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'create address success',
                'data' => $address
            ], 200);
        }catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
