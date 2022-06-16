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
        $data = $this->database->getReference('gudang')->getValue();
        $arr = [];
        $no=0;
        foreach ($data as $key => $value) {

            $arr[$no]['id'] = $value['id'];
            $arr[$no]['name'] = $value['name'];
            $arr[$no]['max_capacity'] = $value['max_capacity'];
            $arr[$no]['min_capacity'] = $value['min_capacity'];
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
            'id'=>$unique,
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
        ->getReference('gudang/' . $request->id)
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

    public function getHtmlOption()
    {
        $data = $this->database->getReference('gudang')->getValue();
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
