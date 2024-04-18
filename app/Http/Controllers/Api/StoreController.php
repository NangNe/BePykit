<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stores;
use App\Models\Images;
use App\Models\StaffStores;
use App\Models\MenuItems;
use Ramsey\Uuid\Uuid;
use App\Models\MenuTypes;
use App\Models\StoreBranchs;

class StoreController extends Controller
{
    //
    public function getStores(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|int',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 200);
            }
            $user = User::where('id', $request->user_id)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'user not found',
                    'error' => 'user not found'
                ], 200);
            }
            $listIDStore = [];
            $staffStores = StaffStores::where('user_id', $request->user_id)->get();
            if (count($staffStores) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'stores not found',
                    'error' => 'stores not found'
                ], 200);
            }
            foreach ($staffStores as $key => $staffStore) {
                $listIDStore[] = [
                    'store_id' => $staffStore->store_id,
                    'role_id' => $staffStore->roleID
                ];
            }
            $stores = [];
            foreach ($listIDStore as $key => $store) {
                $stores[] = Stores::where('id', $store['store_id'])->first();
                $stores[$key]->roleIDuser = $store['role_id'];
            }
            foreach ($stores as $key => $store) {
                $store->images = Images::where('image_type', 'store')
                    ->where('image_query_id', $store->id_stores)
                    ->get();
            }
            return response()->json([
                'status' => true,
                'message' => 'success',
                'stores' => $stores
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

    public function getAllImage($image_id)
    {
        $listUrlImage = [];
        $images = Images::where('image_id', $image_id)->get();
        foreach ($images as $image) {
            $urlImage = asset('images/' . $image->image_url);
            array_push($listUrlImage, $urlImage);
        }
        return $listUrlImage;
    }

    public function addMenuitem(Request $request)
    {
        try {
            //code...
            $uuid = Uuid::uuid4()->toString() . "-" . uniqid();
            $validator = Validator::make(
                $request->all(),
                [
                    'menu_type_id' => 'required|string|max:255',
                    'name_menu_item' => 'required|string|max:255',
                    'price' => 'required|numeric',
                    'unit' => 'required|string|max:255',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validator->errors()
                ], 200);
            }
            $menu_type_id = MenuTypes::where('id_menu_type', $request->menu_type_id)->first()->id;

            if (!$menu_type_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'menu_type_id not found',
                ], 200);
            }

            $create_menuItems = new MenuItems();
            $create_menuItems->id_menu_item = $uuid;
            $create_menuItems->menu_type_id = $menu_type_id;
            $create_menuItems->name_menu_item = $request->name_menu_item;
            $create_menuItems->description = $request->description;
            $create_menuItems->price = $request->price;
            $create_menuItems->status = "Active";
            $create_menuItems->currency = $request->currency;
            $create_menuItems->unit = $request->unit;

            $check = $create_menuItems->save();
            if ($check) {
                if ($request->hasFile('photos')) {
                    foreach ($request->photos as $file) {
                        $type = $file->getClientOriginalExtension();
                        if ($file->extension() == 'jpg' || $type == 'png' || $file->extension() == 'jpeg') {
                            $nameFile = uniqid() . '.' . $type;
                            $file->move(public_path('images'), $nameFile);
                            $image = new Images;
                            $image->image_path = $nameFile;
                            $image->image_type = "menu_item";
                            $image->image_query_id =  $create_menuItems->id_menu_item;
                            $image->image_extension = $type;
                            $image->image_id = $uuid;
                            $image->save();
                        }
                    }
                }
                if ($request->has('image_links')) {
                    foreach ($request->image_links as $link) {
                        $image = new Images;
                        $image->image_url = $link;
                        $image->image_type = "menu_item";
                        $image->image_query_id =  $create_menuItems->id_menu_item;
                        $image->image_extension = "any";
                        $image->image_id = $uuid;
                        $image->save();
                    }
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Add menu item success',
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

    public function getAllMenuItem(Request $request)
    {
        try{
            
            $validateUser = Validator::make(
                $request->all(),
                [
                    // 'store_id' => 'required',
                    // 'store_branch_id' => 'required',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ],400);
            }


            // get all menu item

        }
        catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'error',
                'error' => $th->getMessage()
            ], 200);
        }
    }
}
