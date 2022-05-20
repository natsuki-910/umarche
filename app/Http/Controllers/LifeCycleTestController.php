<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    //
    public function showServiceProviderTest()
    {
        $encrypt = app()->make('encrypter');

        //パスワードを暗号化
        $password = $encrypt->encrypt('password');

        $sample = app()->make('serviceProviderTest');
        //パスワードを戻す
        dd($sample, $password, $encrypt->decrypt($password));

    }

    public function showServiceContainerTest()
    {
        //サービスコンテナに登録したい内容
        app()->bind('lifeCycleTest', function() {
            return 'ライフサイクルテスト';
        });

        $test = app()->make('lifeCycleTest');

        //サービスコンテナなしのパターン
        //それぞれのクラスをすべてインスタンス化する
        $message = new Message();
        $sample = new Sample($message);
        $sample->run();

        //サービスコンテナapp()ありのパターン
        // newでインスタンス化をしなくても使える
        //sampleクラスの内部でmessageクラスも設定の必要があったが、自動的に依存関係を解決してapp()->makeで使えるようになっている
        app()->bind('sample', Sample::class);
        app()->make('sample');
        $sample->run();


        dd($test,app());
    }
}

class Message
{
    public function send() {
        echo ('メッセージ表示');
    }
}

class Sample
{
    public $message;
    public function  __construct(Message $message) {
        $this->message = $message;
    }
    public function run() {
        $this->message->send();
    }
}
