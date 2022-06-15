<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class TransaksiController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        return response()->json($this->database->getReference('transaksi')->getValue());
    }

     public function detail($id){
        $data = $this->database->getReference('transaksi')->getValue();
        //$data[$id];
        return response()->json($data[$id]);
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('transaksi/' . $unique)
        ->set([
            'id'=>$unique,
            'name' => $request->name,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jumlah_transaksi' => $request->jumlah_transaksi
        ]);

        return response()->json('transaksis has been added');
    }

    public function update(Request $request){
        $this->database
        ->getReference('transaksi/' . $request->id_transaksi)
        ->update([
           // 'id_transaksi'=>$unique,
            'name' => $request->name,
            'jenis_transaksi' => $request->jenis_transaksi,
            'jumlah_transaksi' => $request->jumlah_transaksi
        ]);

        return response()->json('transaksi has been updated');
    }

    public function delete(Request $request){
        $this->database
        ->getReference('transaksi/' . $request->id_transaksi)
        ->remove();

        return response()->json('transaksi has been deleted');
    }

}
