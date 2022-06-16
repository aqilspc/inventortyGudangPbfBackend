<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class MaterialController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        $data = $this->database->getReference('material')->getValue();
        $arr = [];
        $no=0;
        foreach ($data as $key => $value) {
            $arr[$no]['stock'] = 0;
            $transaksi = $this->detailTransaksi();
            $maxStok = 0;
            if($transaksi != null){
                foreach ($transaksi as $key => $value1) {
                    if($value['id'] == $value1['material_id']){
                        $maxStok += $value1['qty'];
                    }
                }
                $arr[$no]['stock'] = $maxStok;
            }
            $arr[$no]['id'] = $value['id'];
            $arr[$no]['name'] = $value['name'];
            $arr[$no]['jenis_material'] = $value['jenis_material'];
            $no++;
        }

        if($data != null){
            return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $arr]
                );
        }else{
            return response()->json(
                [
                    "status" => "failed"
                    , "success" =>false
                    , "data" => $arr
                    , "message" => "data not available"]
                );
        }
    }

     public function detail($id){
        $data = $this->database->getReference('material')->getValue();
        //$data[$id];
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $data[$id]]
                );
       // return response()->json();
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('material/' . $unique)
        ->set([
            'id'=>$unique,
            'name' => $request->name,
            //'stock'=>0,
            'jenis_material' => $request->jenis_material,
            'stock' => 0
        ]);
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been added']
                );
    }

    public function update(Request $request){
        $this->database
        ->getReference('material/' . $request->id)
        ->update([
           // 'id_material'=>$unique,
            'name' => $request->name,
            //'stock'=>0,
            'jenis_material' => $request->jenis_material,
            'stock' => $request->stock
        ]);

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been updated']
                );
    }

    public function delete($id){
        $this->database
        ->getReference('material/' . $id)
        ->remove();

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been deleted']
                );
    }

    public function detailTransaksi()
    {
        $data = $this->database->getReference('transaksi')->getValue();
        return $data;
    }

    public function getHtmlOption()
    {
        $data = $this->database->getReference('material')->getValue();
        $result = [];
        if($data != null)
        {
            $no = 0;
            foreach ($data as $key => $value) {
                $result[$no]['value'] = $value['id'];
                $result[$no]['text'] = $value['name'];
                $no++; 
            }
        }
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $result]
                );
    }

}
