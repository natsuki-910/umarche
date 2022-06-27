<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    //認証
    public function __construct()
    {
        $this->middleware('auth:owners'); //オーナーかどうかの確認

        $this->middleware(function($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            // dd(Auth::id()); //数値

            $id = $request->route()->parameter('shop'); //shopのid取得

            if(!is_null($id)) { // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id; //shopテーブルからオーナーのidを取得
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id(); //オーナーID

                if($shopId !== $ownerId){ // 同じでなかったら
                    abort(404); // 404画面表示
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        // ログインしているオーナーが作ったshopを取得
        // $ownerId = Auth::id();
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }
}
