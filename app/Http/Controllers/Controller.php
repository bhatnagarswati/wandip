<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class Controller extends BaseController
{

    use AuthorizesRequests,
    DispatchesJobs,
        ValidatesRequests;

    //for notification
    /**
     * Supported devices to send push Notifications
     * IOS
     * ANDROID
     */
    const DEVICES = ['ios', 'android'];

    /**
     * Pass phrase of IOS
     * @var null
     */
    private $passPhrase = null;

    /**
     * Headers for android
     * @var array
     */
    private $headers = array();

    /**
     * Device Token
     * @var null
     */
    private $deviceToken = null;

    
    protected function loggedUser()
    {
        return auth()->user();
    }

    protected function setResponse($response = [], $status = 200)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        die;
        //return \Response::json($request);

    }

    /* if operation successfully performed
     * @param  int $msg  Message t obe displayed
     * @param  array  $data data to return with success message
     * @return callback
     */

    protected function success($message = "Success", $responseData = [], $status = 200, $token = null)
    {
        $response = [];
        $response['code'] = $status;
        $response['success'] = true;

        if ($token != null) {
            $response['token'] = $token;
            $response['message'] = $message;
            $response['data'] = $responseData;
            return $this->setResponse($response, $status);

        } else {
            $response['message'] = $message;
            $response['data'] = $responseData;
            return $this->setResponse($response, $status);

        }
    }

    /**
     * If operation was'nt performed successfully
     * @param  string $error Error Message
     * @return callback
     */
    public function error($message = "Error occured.", $responseData = null, $status = 400)
    {
        $response = [];
        $response['code'] = $status;
        $response['success'] = false;
        $response['message'] = $message;
        $response['data'] = $responseData;
        return $this->setResponse($response, $status);
    }

    public function validation($request = '', $rules = [], $messages = [])
    {
        $validator = Validator::make($request, $rules, $messages);
        if ($validator->fails()) {

            $this->error(@$validator->errors()->all()[0]);
        }
    }


    

}
