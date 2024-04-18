<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tables;
use App\Models\StaffStores;
use App\Models\StoreBranchs;
use App\Models\MenuTypes;
use App\Models\Stores;
use App\Models\MenuItems;
use App\Models\Floors;
use App\Models\Orders;

class OrderController extends Controller
{
    //
    public function getOrderToday(Request $request)
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
                ],400);
            }
            $listOrderToday=[];
            $listIdTable=[];
            $store=Stores::where('id_stores',$request->store_id)->first();
            if(!$store){
                return response()->json([
                    'status' => false,
                    'message' => 'store not found',
                ],404);
            }
            $storeBranch = StoreBranchs::where('store_id',$store->id)->where('id_store_branch',$request->store_branch_id)->first();
            $tables=Tables::where('stores_branch_id',$storeBranch->id)->get();
            foreach ($tables as $key => $table) {
                $listIdTable[]=[
                    'id_table'=>$table->id,
                    'id_floor'=>Floors::where('id',$table->floor_id)->first()->id_floors,
                    'number_floor'=>Floors::where('id',$table->floor_id)->first()->floor_number,
                ];
            }
            foreach ($listIdTable as $key => $table) {
                $orders=Orders::where('table_id',$table['id_table'])->get();
                if($orders){
                    foreach ($orders as $key => $order) {
                        $order->floor_id=$table['id_floor'];
                        $order->table_id=Tables::where('id',$table['id_table'])->first()->id_table;
                        $order->number_table=Tables::where('id',$table['id_table'])->first()->number_table;
                        $order->number_floor=$table['number_floor'];
                        $order->staff_id=StaffStores::where('id',$order->staff_id)->first()->id_staff_store;
                        $listOrderToday[]=$order;
                    }
                }
            }
            foreach ($listOrderToday as $key => $order) {
                $order->order_items;
                foreach ($order->order_items as $key => $orderItem) {
                    $orderItem->menu_item;
                    $orderItem->menu_item->images;

                    $orderItem->time_create=date('h:i A',strtotime($orderItem->created_at));
                    $orderItem->order_id=Orders::where('id',$orderItem->order_id)->first()->id_order;
                    $orderItem->menu_item_id=MenuItems::where('id',$orderItem->menu_item_id)->first()->id_menu_item;
                    $orderItem->menu_item->menu_type_id = MenuTypes::where('id',$orderItem->menu_item->menu_type_id)->first()->id_menu_type;
                }
            }
            // sort listOrderToday by created_at desc
            $listOrderToday = collect($listOrderToday)->sortByDesc('created_at')->values()->all();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'orders' => $listOrderToday
            ],200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'error',
                'error' => $th->getMessage()
            ],500);
        }
    }
}
