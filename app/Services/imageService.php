<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
  public static function upload($imageFile,$folderName) {

    //リサイズありの場合
    $fileName = uniqid(rand().'_'); //ランダムなファイル名を作成
    $extension = $imageFile->extension(); //拡張子を取得
    $fileNameToStore = $fileName. '.' . $extension; //ファイル名+拡張子
    $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
    Storage::put('public/' . $folderName . '/' . $fileNameToStore,$resizedImage );
    return $fileNameToStore;
  }
}