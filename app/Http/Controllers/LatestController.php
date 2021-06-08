<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badges;
use App\Models\CatalogItems as Furni;
class LatestController extends Controller
{
    public function badge(Request $request, $limit = 15) {
        $query = Badges::orderByDesc("id")->limit($limit)->cursor();
        foreach($query as $badge) {
            $json[$badge->code]['rozet_resim_link'] = "https://cdn.runo.pw/c_images/album1584/" . $badge->code . ".gif";
            $json[$badge->code]['rozet_kodu'] = $badge->code;
            $json[$badge->code]['rozet_isim'] = $badge->name;
            $json[$badge->code]['rozet_aciklama'] = $badge->description !== null ? $badge->description : "Açıklama yok";
        }
        return \response()->json($json, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
    public function furni(Request $request, $limit = 15) {
        if(!$limit) {
            $limit = 15;
        }
        $query = Furni::orderByDesc("id")->limit($limit)->cursor();
        foreach($query as $furni) {
            $json[$furni->id]['isim'] = $furni->catalog_name;
            $json[$furni->id]['kredi'] = $furni->cost_credits;
            $json[$furni->id]['puan'] = $furni->cost_points;
            $json[$furni->id]['puan_turu'] = $furni->points_type == "5" ? "elmas" : ($furni->points_type == "0" ? "0" : "diger");
            $json[$furni->id]['ltd_mi'] = $furni->limited_stack > 0 ? "evet" : "hayir";
            if($json[$furni->id]['ltd_mi'] == "evet") {
                $json[$furni->id]['ltd_satilan'] = $furni->limited_sells;
                $json[$furni->id]['ltd_kalan'] = $furni->limited_stack - $furni->limited_sells;
            }
            
        }
        return \response()->json($json, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
}
