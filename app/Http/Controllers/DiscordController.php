<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DiscordController extends Controller
{
    public function login(Request $request, $id)
    {
        $json = [];
        // grab the user from db
        if (!is_numeric($id)) {
            $json['code'] = 401;
            $json['message'] = 'Kullanıcı ID\'si bir sayı değil.';
            return response()->json($json);
        }
        $user = User::where('id', $id)->first();
        if ($user) {
            $discordId = "";
            // curl request to bot's url
            $ch = curl_init("http://bot.runo.pw:2086/login?id=" . $user->discord_id . "&username=" . $user->username);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            # Send request.
            $result = curl_exec($ch);
            
            # Print response.
            if($result == "OK") {
                // ok
                return response()->json([
                    'code' => 200,
                    'message' => 'OK'
                ]);
            } else {
                 // nok
                 return response()->json([
                    'code' => 403,
                    'message' => $result,
                    "data" => [
                        "user" => $user
                    ]
                ]);
            }
            curl_close($ch);
        } else {
            // user was not found
            $json['code'] = 404;
            $json['message'] = 'Kullanıcı bulunamadı.';
            return response()->json($json);
        }
    }
}
