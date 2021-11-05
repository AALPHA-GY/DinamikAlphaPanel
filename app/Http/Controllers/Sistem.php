<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kolonlar;
use App\Models\Bilgiler;

class Sistem extends Controller
{
    public function index()
    {
      $kolonlar=Kolonlar::orderBy("sirano","asc")->get();
      $diziler=[];
      foreach ($kolonlar as $kolon) {
        $task=[];
        $bilgiler=Bilgiler::whereKolonid($kolon->id)->orderBy("sirano","asc")->get();
        foreach ($bilgiler as $bilgi) {
          $task[]=[
            "id"=> $bilgi->id,
            "kolonid"=> $bilgi->kolonid,
            "title"=> $bilgi->baslik,
            "slug"=> $bilgi->slug,
            "metin"=> $bilgi->metin
          ];
        }
        $diziler[]=[
          "id"=>$kolon->id,
          "title"=>$kolon->baslik,
          "edit"=>false,
          "slug"=>$kolon->slug,
          "tasks"=>$task
        ];
      }
      $jsonData=json_encode($diziler);
      return view('welcome',compact('jsonData'));
    }

    public function kolonAdd(Request $request)
    {
      $request->validate([
        'baslik'=>'required|unique:App\Models\Kolonlar,baslik',
      ]);
      $kolonlar=Kolonlar::orderBy("id","desc")->get();
      if($kolonlar){$sirano=$kolonlar[0]->id; $sirano++;}else{$sirano=1;}
      $baslik=$request->baslik;
      $slug = Str::slug($baslik, '-');
      $ekle=Kolonlar::create([
        "baslik"=>$baslik,
        "slug"=>$slug,
        "sirano"=>$sirano
      ]);
      $sonid = Kolonlar::latest()->first()->id;
      return response()->json(['status' => "success",'id'=>$sonid,'baslik'=>$baslik,'slug'=>$slug], 200);
    }

    public function kolonUpp(Request $request)
    {
      $request->validate([
        'baslik'=>'required',
        'kolonkodu'=>'required',
      ]);
      $baslik=$request->baslik;
      $kolonid=$request->kolonkodu;
      $kolonlar=Kolonlar::whereId($kolonid)->first();
      if($kolonlar){
        $sirano=$kolonlar->sirano;
        $slug = Str::slug($baslik, '-');
        $guncelle=Kolonlar::whereId($kolonid)->update([
          "baslik"=>$baslik,
          "slug"=>$slug
        ]);
        return response()->json(['status' => "success",'id'=>$kolonlar->id,'baslik'=>$baslik,'slug'=>$slug], 200);
      }
      else{
        return response()->json(['status' => "error"], 401);
      }
    }

    public function sutunAdd(Request $request)
    {
      $request->validate([
        'baslik'=>'required',
        'kolonno'=>'required',
      ]);
      $sutunlar=Bilgiler::orderBy("id","desc")->get();
      if($sutunlar){$sirano=$sutunlar[0]->id; $sirano++;}else{$sirano=1;}
      $baslik=$request->baslik;
      $slug = Str::slug($baslik, '-');
      $kolonid=$request->kolonno;
      $ekle=Bilgiler::create([
        "baslik"=>$baslik,
        "kolonid"=>$kolonid,
        "slug"=>$slug,
        "sirano"=>$sirano
      ]);
      $sonid = Bilgiler::latest()->first()->id;
      return response()->json(['status' => "success",'id'=>$sonid,'kolonid'=>$kolonid,'baslik'=>$baslik,'slug'=>$slug,'metin'=>''], 200);
    }

    public function tasima(Request $request)
    {

      $request->validate([
        'veriler'=>'required',
      ]);

      /*gelen json verisini diziye çevir*/
      $veriler=$request->veriler;
      foreach ($veriler as $key=>$value) {
        $kolonid=$value["id"];
        $tasks=$value["tasks"];//multi dizi
        $sirano=1;
        foreach ($tasks as $er=>$sutunlar) {
          $sutunid=$sutunlar["id"];
          $guncelle=Bilgiler::whereId($sutunid)->update([
            "kolonid"=>$kolonid,
            "sirano"=>$sirano
          ]);
          $sirano++;
        }
      }
      return response()->json(['status' => "success"], 200);

    }
}
