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
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $this->database->getReference('gudang')->getValue()]
                );
    }

     public function detail($id){
        $data = $this->database->getReference('gudang')->getValue();
        //$data[$id];
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $data[$id]]
                );
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
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'warehouse has been added']
                );
    }

    public function update(Request $request){
        $this->database
        ->getReference('gudang/' . $request->id_gudang)
        ->update([
            'name' => $request->name,
            'max_capacity' => $request->max_capacity,
            'min_capacity' => $request->min_capacity,
        ]);
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'warehouse has been updated']
                );
        //return response()->json('warehouse has been updated');
    }

    public function delete($id){
        $this->database
        ->getReference('gudang/' . $id)
        ->remove();

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'warehouse has been deleted']
                );
    }

}
