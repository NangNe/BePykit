<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\TokenAccess;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Profiler\Profile;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|string|max:255|min:3',
                    'last_name' => 'required|string|max:255|min:1',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 200);
            }
            $name = trim($request->first_name . $request->last_name);
            $name = strtolower($name);
            $name = preg_replace('/[^a-zA-Z0-9]/', '-', $name);
            $name = preg_replace('/-{2,}/', '-', $name);
            $name = trim($name, '-');
            $check = $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roleID' => $request->roleID != null ? $request->roleID : 2,
                'status' => 'Active',
            ]);
            if (!$check) {
                return response()->json([
                    'status' => false,
                    'message' => 'register failed',
                ], 400);
            }

            $uuid = Uuid::uuid4()->toString() . "-" . uniqid();
            $profile = new ProfileUsers();
            $profile->id_profile_user = $uuid;
            $profile->user_id = $user->id;
            $profile->image_id = 'default.png';
            $profile->cover_image_id = 'default.png';
            $profile->nick_name = $name;
            $profile->phone_number = null;
            $profile->address = null;
            $profile->date_of_birth = null;
            $profile->gender = null ? $profile->gender : 'Other';
            $profile->hashtag = '#' . $name;
            $profile->save();
            // $user->profile = $profile;

            $token = $user->createToken("API TOKEN")->plainTextToken;
            // update remember token
            $user->remember_token = $token;
            $user->update();
            return response()->json([
                'status' => true,
                'message' => 'register success',
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:6',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 200);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email Password  does not match with our records',
                ], 200);
            }
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            $now = now();
            $user->tokens()->update(['last_used_at' => $now->addMonths(2)]);
            return response()->json([
                'status' => true,
                'message' => 'login success',
                'token' => $token,
                'roleID' => $user->roleID,
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }

    public function loginByToken(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'position' => 'required|string|max:255',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 200);
            }
            $tokendata = $request->bearerToken();
            // config token 
            $tokenfind = PersonalAccessToken::findToken($tokendata)->token;
            $token = TokenAccess::where('token', $tokenfind)->first();
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token does not match with our records',
                    'token' => $token,
                ], 200);
            }
            $user = User::where('id', $token->tokenable_id)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not match with our records',
                ], 200);
            }
            if ($user->roleID != $request->position) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error Positio',
                ], 200);
            }
            $now = now();
            $user->tokens()->update(['last_used_at' => $now->addMonths(5)]);
            return response()->json([
                'status' => true,
                'message' => 'login success',
                'roleID' => $user->roleID,
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }

    public function managerLiveLogin(Request $request)
    { {
            try {
                //code...
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'email' => 'required|string|email|max:255',
                        'password' => 'required|string|min:6',
                    ]
                );
                if ($validateUser->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validate error',
                        'error' => $validateUser->errors()
                    ], 200);
                }
                if (!Auth::attempt($request->only(['email', 'password']))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email Password  does not match with our records',
                    ], 200);
                }
                $user = User::where('email', $request->email)->first();
                $user->getStaffPlaces;
                if ($user->roleID != '2' && $user->getStaffPlaces->count() > 0) {
                    $token = $user->createToken("API TOKEN")->plainTextToken;
                    $now = now();
                    $user->tokens()->update(['last_used_at' => $now->addMonths(5)]);
                    foreach ($user->getStaffPlaces as $key => $value) {
                        $value->getPlaces;
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'login success',
                        'token' => $token,
                        'roleID' => $user->roleID,
                        'user' => $user
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are not allowed to login here',
                    ], 200);
                }
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),

                ], 200);
            }
        }
    }

    public function managerLiveLoginByToken(Request $request)
    {
        try {
            //code...
            $validateUser = Validator::make(
                $request->all(),
                [
                    'position' => 'required|string|max:255',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validate error',
                    'error' => $validateUser->errors()
                ], 200);
            }
            $tokendata = $request->bearerToken();
            // config token 
            $tokenfind = PersonalAccessToken::findToken($tokendata)->token;
            $token = TokenAccess::where('token', $tokenfind)->first();
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token does not match with our records',
                    'token' => $token,
                ], 200);
            }
            $user = User::where('id', $token->tokenable_id)->first();
            $user->getStaffPlaces;
            if ($user->roleID == $request->position && $user->getStaffPlaces->count() > 0) {
                $now = now();
                $user->tokens()->update(['last_used_at' => $now->addMonths(5)]);
                foreach ($user->getStaffPlaces as $key => $value) {
                    $value->getPlaces;
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to login here',
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'login success',
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 200);
        }
    }
    public function adminLogin(Request $request)
    { {
            try {
                //code...
                $validateUser = Validator::make(
                    $request->all(),
                    [
                        'email' => 'required|string|email|max:255',
                        'password' => 'required|string|min:6',
                        'position' => 'required|string|max:255',
                    ]
                );
                if ($validateUser->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validate error',
                        'error' => $validateUser->errors()
                    ], 200);
                }
                if (!Auth::attempt($request->only(['email', 'password']))) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Email Password  does not match with our records',
                    ], 200);
                }
                $user = User::where('email', $request->email)->first();
                if ($user->roleID == $request->position || $user->roleID == "0" || $user->roleID == "1") {
                    $token = $user->createToken("API TOKEN")->plainTextToken;
                    $now = now();
                    $user->tokens()->update(['last_used_at' => $now->addMonths(5)]);
                    return response()->json([
                        'status' => true,
                        'message' => 'login success',
                        'token' => $token,
                        'roleID' => $user->roleID,
                        'user' => $user
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are not admin',
                    ], 200);
                }
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),

                ], 200);
            }
        }
    }


    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="logout success"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Logout error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="logout fail"),
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            //code...
            $user = User::where('email', $request->email)->first();
            $tokendata = $request->bearerToken();
            $check = $user->tokens()->where('id', $tokendata)->delete();
            if ($check) {
                return response()->json([
                    'status' => true,
                    'message' => 'logout success',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'logout fail',
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 450);
        }
    }
    public function logoutAll(Request $request)
    {
        try {
            //code...
            $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'logout success',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 450);
        }
    }

    public function findUser(Request $request)
    {
        try {

            $search = $request->query('search');
            $query = $request->query('query');
            $users =[];


            // $query = User::whereHas('profile', function ($query) use ($search) {
            //     $query->where('nick_name', 'LIKE', '%' . $query . '%')
            //         ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
            //         ->orWhere('address', 'LIKE', '%' . $query . '%')
            //         ->orWhere('email', 'LIKE', '%' . $query . '%')
            //         ->orWhere('gender', 'LIKE', '%' . $query . '%');
            // })->get();

            // if ($query->count() == 0) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'search is null',
            //     ], 200);
            // }

            // foreach ($query as $key => $value) {
            //     $value->profile;
            //     $result = ProfileUsers::where('user_id', $value->id)->first();
            //     $value->profile = $result;
            // }
            
            $dataSearch = $search? User::where('first_name','LIKE', '%' . $query . '%')->
                                        orWhere('last_name','LIKE', '%' . $query . '%')->
                                        orWhere('email','LIKE', '%' . $query . '%')->get() : null;

            $dataSearch2 = $search? ProfileUsers::where('phone_number', 'LIKE', '%' . $query . '%')->get() : null;
                
            if ($dataSearch) {
                foreach ($dataSearch as $key => $value) {
                    $value->profile;
                    $users[] = $value;
                }
            }

            if($dataSearch2){
                foreach ($dataSearch2 as $key => $value) {
                    $value->user;
                    $users[] = $value->user;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'search success',
                'users' => $users,   
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
