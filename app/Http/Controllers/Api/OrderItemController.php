<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderItems;
use App\Models\Promotions;
use App\Models\Images;
use App\Models\StoreBranchs;

class OrderItemController extends Controller
{
    //
    public function changeStatusOrderItem(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'order_item_id' => 'required',
                    'status' => 'required',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 400);
            }
            $orderItem = OrderItems::where('id_order_item', $request->order_item_id)->first();
            if (!$orderItem) {
                return response()->json([
                    'status' => false,
                    'message' => 'order item not found',
                ], 404);
            }
            $orderItem->status = $request->status;
            $orderItem->save();
            return response()->json([
                'status' => true,
                'message' => 'change status order item success',
                'id' => $orderItem->id_order_item,
                'status_change' => $orderItem->status,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'change status order item fail',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function changeQuantity(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'id_order_item' => 'required',
                    // quantity is number and min is 1
                    'quantity' => 'required|numeric|min:1|',

                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 400);
            }
            $quantity = OrderItems::where('id_order_item', $request->id_order_item)->first();
            if (!$quantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'order item not found',
                ], 200);
            }
            $quantity->quantity = $request->quantity;
            $quantity->save();
            return response()->json([
                'status' => true,
                'message' => 'change quantity success',
                'id' => $quantity->id_order_item,
                'quantity_change' => $quantity->quantity,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'change quantity fail',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getAllPromotion(Request $request)
    {
        try {
            //code...
            $validator = Validator::make(
                $request->all(),
                [
                    'stores_branch_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validator->errors()
                ], 400);
            }
            $store_branch_id = StoreBranchs::where('id_store_branch', $request->stores_branch_id)->first()->id; 
            $promotions = Promotions::where('stores_branch_id', $store_branch_id)->where('status', 'Active')->limit(5)->get();
            
            if (count($promotions) > 0) {
                foreach ($promotions as $promotion) {
                   $promotion ->images;
                }
            }
             return response()->json([
                'status' => true,
                'message' => 'get all promotion success',
                'promotions' => $promotions
            ], 200);   
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'get all promotion fail',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
