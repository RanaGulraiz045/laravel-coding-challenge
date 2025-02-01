<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Laravel APIs Documentation",
 *     version="1.0.0",
 *     description="This is a sample server for a Laravel application.",
 *     @OA\Contact(
 *       email="support@example.com",
 *       name="Support Team"
 *     )
 *   ),
 *   @OA\Server(
 *     description="Main Server",
 *     url="http://127.0.0.1:8000/api"
 *   ),
 *    @OA\Components(
 *        @OA\SecurityScheme(
 *            securityScheme="bearerAuth",
 *            type="http",
 *            scheme="bearer",
 *            bearerFormat="JWT",
 *            description="Input your Bearer token in this format - Bearer {your_token_here} to authorize"
 *        )
 *    )
 * )
 */

class AuthController extends Controller
{      
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Log in a user",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email address"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User password"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Authentication token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => false,
                'message' => $validation->errors()->first()
            ];
            return response()->json($response);
        }

        $attempt = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$attempt)
        {
            $response = [
                'status' => false,
                'message' => 'Invalid Email or Password'
            ];
            return response()->json($response);
        }

        $user = Auth::user();
        $token = $user->createToken('MyApp');
        $user['access_token'] = $token->accessToken;
        $response = [
            'status'  => true,
            'message' => 'Login Successfully',
            'data'    => $user,
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/signup",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     operationId="signup",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email", "password", "name", "invitation_token"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email address"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User password"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="User's full name"
     *                 ),
     *                 @OA\Property(
     *                     property="invitation_token",
     *                     type="string",
     *                     description="Invitation token for user registration"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User registered successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid data provided"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Could not register user"
     *             )
     *         )
     *     )
     * )
     */
    public function signup(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'invitation_token' => 'required',
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => false,
                'message' => $validation->errors()->first()
            ];
            return response()->json($response);
        }
        $invitation = Invitation::where('token', $request->invitation_token)
                                ->where('email', $request->email)
                                ->where('expires_at', '>', now())
                                ->where('accepted', false)
                                ->first();

        if (!$invitation) {
            $response = [
                'status' => false,
                'message' => 'Invalid or expired invitation'
            ];
            return response()->json($response);
        }

        $invitation->update(['accepted' => true]);

        $isEmailExist = User::where('email',$request->email)->first();
        if($isEmailExist){
            return response()->json([
                'status' => false,
                'message' => 'Email Already Exists',
            ]);

        }
        else{
            $user = new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password= Hash::make($request->password);

            if($user->save()){
                $token = $user->createToken('MyApp');
                $accessToken = $token->accessToken;

                $user['access_token'] = $accessToken;

                $response = [
                    'status' => true,
                    'message' => 'Signed up successfully',
                    'data' => $user
                ];
                return response()->json($response);
            }
            else{
                $response = [
                    'status' => true,
                    'message' => 'Something went wrong',
                ];
                return response()->json($response);
            }
        }
    }

}
