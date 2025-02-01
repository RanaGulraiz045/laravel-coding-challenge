<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/admin/create-invitation",
     *     tags={"Admin Operations"},
     *     summary="Create invitation to a user",
     *     operationId="createInvitation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email to create the invitation",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email address to create the invitation"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invitation sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invitation successfully sent to the provided email."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid email format or missing email field",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid request data"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error - Unable to create invitation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Internal server error"
     *             )
     *         )
     *     )
     * )
     */
    public function createInvitation(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => false,
                'message' => $validation->errors()->first()
            ];
            return response()->json($response);
        }
        
        $token = Str::random(60);
        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addDays(7)  // Set expiration to 7 days
        ]);

        Log::info("Invitation created with token: {$token} for email: {$request->email}");

        $response = [
            'status'  => true,
            'message' => 'Invitation Sent',
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/admin/resend-invitation",
     *     tags={"Admin Operations"},
     *     summary="Resend invitation to a user",
     *     operationId="resendInvitation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email to resend the invitation",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email address to resend the invitation"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invitation resent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invitation successfully resent to the provided email."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid email format or missing email field",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid request data"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing authentication token",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error - Unable to resend invitation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Internal server error"
     *             )
     *         )
     *     )
     * )
     */
    public function resendInvitation(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validation->fails()) {
            $response = [
                'status' => false,
                'message' => $validation->errors()->first()
            ];
            return response()->json($response);
        }

        $invitation = Invitation::where('email', $request->email)->first();
        if($invitation){
            $invitation->update([
                'token' => Str::random(60),
                'expires_at' => now()->addDays(7),
                'accepted' => false
            ]);
            Log::info("Resend Invitation token: {$invitation->token}");

            $response = [
                'status'  => true,
                'message' => 'Invitation Resend',
            ];
            return response()->json($response);
        }
        $response = [
            'status'  => false,
            'message' => 'Invitation Not Found Against the Email',
        ];
        return response()->json($response);
    }

}
