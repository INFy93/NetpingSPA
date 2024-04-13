<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Netping;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CameraController extends Controller
{
    public function getCamera($netping_id)
    {
        header("Content-Type: image/jpeg");
        $login = env('CAM_LOGIN');
        $pass = env('CAM_PASS');
        $link = env('CAM_LINK');

        $netping = Netping::find($netping_id);

        try {
            $screenshot = HTTP::timeout(env('NETPING_TIMEOUT'))
                ->withHeaders([
                    'Content-Type' => 'image/jpeg'
                ])
                ->withBasicAuth($login, $pass)
                ->get($netping->camera_ip . $link);
        } catch (ConnectionException $exp) {
            return "Не удалось подключиться к камере";
        }
        $img = imagecreatefromstring($screenshot);
        imagejpeg($img);
        imagedestroy($img);
        return $img;
    }
}
