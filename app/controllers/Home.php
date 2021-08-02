<?php
class Home extends Controller 
{
    // public function index(){
    //     $db = $this->load_model("Db");
    //     $db->createDatabases();
    //     // $this->load_view('index',[]);
    // }


    public function index(){
        $this->sendJSON(Array("name" =>"Adekunle Ajasin"));
    }




}






?>
