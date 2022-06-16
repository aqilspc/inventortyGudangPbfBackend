<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
class TransaksiController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        $transaksi = $this->database->getReference('transaksi')->getValue();
       // $data = $this->database->getReference('gudang')->getValue();
        $arr = [];
        $no = 0;
        if($transaksi != null){
            foreach ($transaksi as $tr => $trItem) {
                $warehouse = $this->detailGudang($trItem['warehouse_id']);
                $material = $this->detailMaterial($trItem['material_id']);
                $user = $this->detailUser($trItem['user_id']);
                $trId = $trItem['id'];
                $arr[$no]['id'] = $trId;
                if($material != null || !is_null($material))
                {
                    $arr[$no]['material_name'] = $material['name'];
                }else{
                    $arr[$no]['material_name'] = 'Data was deleted!';
                }

                if($warehouse != null || !is_null($warehouse))
                {
                    $arr[$no]['warehouse_name'] = $warehouse['name'];
                }else{
                    $arr[$no]['warehouse_name'] = 'Data was deleted!';
                }

                if($user != null || !is_null($user))
                {
                    $arr[$no]['user_name'] = $user['name'];
                }else{
                    $arr[$no]['user_name'] = 'Data was deleted!';
                }

                 $arr[$no]['date_transaction'] = $trItem['date_transaction'];
                 $arr[$no]['qty'] = $trItem['qty']; 
                 $arr[$no]['type'] = $trItem['type']; 
                 $no++;
            }
        }
       if($transaksi != null){
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


    public function insert(Request $request)
    {
        $warehouse = $this->detailGudang($request->warehouse_id);
        $material = $this->detailMaterial($request->material_id);
        $user = $this->detailUser($request->user_id);
        if($warehouse == null){
           return response()->json(
                    [
                        "status" => "failed"
                        , "success" =>false
                        , "message" => "Warehouse must be select"]
                    ); 
        }

        if($material == null){
           return response()->json(
                    [
                        "status" => "failed"
                        , "success" =>false
                        , "message" => "material must be select"]
                    ); 
        }

        if($user == null){
           return response()->json(
                    [
                        "status" => "failed"
                        , "success" =>false
                        , "message" => "user must be select"]
                    ); 
        }
        $transaksi = $this->detailTransaksi();
        // if($request->type == 0){
        //      return response()->json(
        //             [
        //                 "status" => "failed"
        //                 , "success" =>false
        //                 , "message" => "type must be select"]
        //             );
        // }
        if($request->type == 'material_in'){
            $maxStok = $request->qty;
            if($transaksi != null){
                foreach ($transaksi as $key => $value) {
                    if($request->warehouse_id == $value['warehouse_id']){
                        $maxStok += $value['qty'];
                    }
                }
            }


            if($maxStok > $warehouse['max_capacity']){
                return response()->json(
                    [
                        "status" => "failed"
                        , "success" =>false
                        , "message" => "Warehouse quota full maximum"]
                    );
            }
        }else{
            $maxStok = $request->qty;
            if($transaksi != null){
                foreach ($transaksi as $key => $value) {
                    if($request->warehouse_id == $value['warehouse_id']){
                        $maxStok += $value['qty'];
                    }
                }
            }


            if($maxStok < $warehouse['min_capacity']){
                return response()->json(
                    [
                        "status" => "failed"
                        , "success" =>false
                        , "message" => "Warehouse quota cannot out of minimum capacity"]
                    );
            }
        }

        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('transaksi/' . $unique)
        ->set([
            'id'=>$unique,
            'user_id' => $request->user_id,
            'material_id' => $request->material_id,
            'warehouse_id' => $request->warehouse_id,
            'qty' => $request->qty,
            'type' => $request->type,
            'date_transaction'=>Carbon::now()->format('Y-m-d')
        ]);
        $material = $this->detailMaterial($request->material_id);
        $fixStok = $material['stock'] + $request->qty;
         $this->database
        ->getReference('material/' . $material['id'])
        ->update([
            'stock'=>$fixStok
        ]);
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'transaction has been added']
                );
    }

    public function detailGudang($id)
    {
        $data = $this->database->getReference('gudang')->getValue();
        if(!isset($data[$id]))
        {
            return null;
        }
        return $data[$id];
    }

    public function detailMaterial($id)
    {
        $data = $this->database->getReference('material')->getValue();
        if(!isset($data[$id]))
        {
            return null;
        }
        return $data[$id];
    }

    public function detailUser($id)
    {
        $data = $this->database->getReference('user')->getValue();
        if(!isset($data[$id]))
        {
            return null;
        }
        return $data[$id];
    }

    public function detailTransaksi()
    {
        $data = $this->database->getReference('transaksi')->getValue();
        return $data;
    }

}
