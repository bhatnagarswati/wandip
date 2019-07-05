<?php

namespace App\Http\Middleware;

use App;
use Closure;
use DB;
use Session;

class APIToken
{

    public function handle($request, Closure $next)
    {
		
		$access_type = $request->header('accessType');
		$user_type = $request->header('userType');

        if ($request->header('AuthorizationToken') && $request->header('userId') &&  $access_type != 'adBlueFreeAccess') {
            $userId = $request->header('userId');
            $user_type = $request->header('userType');
            $accesstoken = $request->header('AuthorizationToken');
            $token = DB::table('tokens')->where(['user_id' => $userId, 'user_type' => $user_type, 'token_status' => 1])->first();
            $response = [];
            if (!empty($token)) {
                if ($token->access_token == $accesstoken) {
                    return $next($request);
                } else {
                    $response['code'] = 403;
                    $response['success'] = false;
                    $response['message'] = 'Invalid token.';
                    $response['data'] = '';
                    return response()->json($response, 403);
                }
            } else {
                $response['code'] = 401;
                $response['success'] = false;
                $response['message'] = 'Unauthorised';
                $response['data'] = '';
                return response()->json($response, 401);
            }
		} else if ($user_type == 'customer' && $access_type == 'adBlueFreeAccess' && !$request->header('AuthorizationToken') && !$request->header('userId')) {
			return $next($request);
		} else {
            $response['code'] = 401;
            $response['success'] = false;
            $response['message'] = 'Not a valid API request.';
            $response['data'] = '';
            return response()->json($response, 401);
        }
    }

}
