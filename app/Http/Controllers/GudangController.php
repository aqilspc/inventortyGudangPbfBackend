<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class GudangController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        return response()->json($this->database->getReference('gudang')->getValue());
    }

     public function detail($id){
        $data = $this->database->getReference('gudang')->getValue();
        //$data[$id];
        return response()->json($data[$id]);
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('gudang/' . $unique)
        ->set([
            'id_gudang'=>$unique,
            'name' => $request->name,
            'max_capacity' => $request->max_capacity,
            'min_capacity' => $request->min_capacity,
        ]);

        return response()->json('warehouse has been created');
    }

    public function update(Request $request){
        $this->database
        ->getReference('gudang/' . $request->id_gudang)
        ->update([
            'name' => $request->name,
            'max_capacity' => $request->max_capacity,
            'min_capacity' => $request->min_capacity,
        ]);

        return response()->json('warehouse has been updated');
    }

    public function delete(Request $request){
        $this->database
        ->getReference('gudang/' . $request->id_gudang)
        ->remove();

        return response()->json('warehouse has been deleted');
    }

}
