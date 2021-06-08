<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rooms;
use App\Models\UsersBadges as Badges;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function info(Request $request, $param) {
        if(!$param) {
            http_response_code(404);
            return \response()->json([
                "hata" => "ID/Kullanıcı adı bilgisi istek üzerinde yok",
                "hata_kodu" => 404
            ]);
        }
        $id = 0;
        $username = "";
        if(is_numeric($param)) {
            // id
            $username = "";

            $id = $param;
        } elseif(is_string($param)){
            // username
            $username = $param;
            $id = 0;
        }
        $user = User::where('id', $id)->orWhere('username' ,$username)->first();
        if(!$user) {
            http_response_code(404);
            return \response()->json([
                "hata" => "Kullanıcı bulunamadı",
                "hata_kodu" => 404
            ]);
        } else {
            $points = DB::table('users_currency')->where([
                ["user_id", "=", $user->id],
                ["type", "=", 5]
            ])->value("amount");
            $rankName = DB::table('permissions')->where("id", $user->rank)->value("rank_name");
            $json = array();
            $activityPoints = DB::table('users_settings')->where('user_id', $user->id)->value("achievement_score");
            $respectGiven = DB::table('users_settings')->where('user_id', $user->id)->value("respects_given");
            $respectReceived = DB::table('users_settings')->where('user_id', $user->id)->value("respects_received");
            $json['id'] = $user->id;
            $json['kullanici_adi'] = $user->username;
            $json['kredi'] = $user->credits;
            $json['elmas'] = $points;
            $json['kiyafet'] = $user->look;
            $json['son_giris'] = $user->last_online;
            $json['motto'] = $user->motto;
            $json['rank'] = $rankName;
            $json['cevrim_ici'] = $user->online == "1" ? "evet" : "hayir";
            $json['basari_puani'] = $activityPoints;
            $json['alinan_saygi'] = $respectReceived;
            $json['verilen_saygi'] = $respectGiven;
            $badges = Badges::where('user_id', $user->id)->cursor();
            $rooms = Rooms::where('owner_id', $user->id)->cursor();
            $json['odalar'] = [];
            $json['odalar']['sayi'] = $rooms->count();
            foreach($rooms as $room) {
                $temp['oda_isim'] = $room->name;
                $temp['aciklama'] = $room->description;
                $state = $room->state;
                if($state == "open") {
                    $temp['giris_durumu'] = "acik";
                } elseif($state == "locked") {
                    $temp['giris_durumu'] = "zil";
                }
                elseif($state == "password") {
                    $temp['giris_durumu'] = "sifre";
                } else {
                    $temp['giris_durumu'] = "sifre";
                }
                $temp['su_anki_kullanici_sayisi'] = $room->users;
                array_push($json['odalar'], $temp);
            }
            $json['rozetler'] = [];
            $json['rozetler']['sayi'] = $badges->count();
            foreach($badges as $badge) {
                
                $temp['rozet_resim_link'] = "https://cdn.runo.pw/c_images/album1584/" . $badge->badge_code . ".gif";
                $temp['rozet_kodu'] = $badge->badge_code;
                $temp['takili_mi'] = $badge->slot_id > 0 ? "evet" : "hayir";
                array_push($json['rozetler'],$temp);
            }
            http_response_code(200);
            return response()->json($json, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
        }
    }
    public function badge(Request $request, $param) {
        if(!$param) {
            http_response_code(404);
            return \response()->json([
                "hata" => "ID/Kullanıcı adı bilgisi istek üzerinde yok",
                "hata_kodu" => 404
            ]);
        }
        $id = 0;
        $username = "";
        if(is_numeric($param)) {
            // id
            $username = "";

            $id = $param;
        } elseif(is_string($param)){
            // username
            $username = $param;
            $id = 0;
        }
        $user = User::where('id', $id)->orWhere('username', $username)->first();
        if(!$user) {
            http_response_code(404);
            return \response()->json([
                "hata" => "Kullanıcı bulunamadı",
                "hata_kodu" => 404
            ]);
        } else {
            $badges = Badges::where('user_id', $user->id)->cursor();
            $json = [];
            foreach($badges as $badge) {
                
                $temp['rozet_resim_link'] = "https://cdn.runo.pw/c_images/album1584/" . $badge->badge_code . ".gif";
                $temp['rozet_kodu'] = $badge->badge_code;
                $temp['takili_mi'] = $badge->slot_id > 0 ? "evet" : "hayir";
                array_push($json,$temp);
            }
            return \response()->json($json);
        }
    }
    public function apiCodes(Request $request){
        $codes = DB::table('api_codes')->get();
        $json = [];
        foreach($codes as $code){
            $temp['code'] = $code->code;
            array_push($json, $temp);
        }
        return response()->json($json);
    }
}
