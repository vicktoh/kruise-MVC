<?php
class DataDb extends Model{

    function __construct(){
        parent::__construct();
    }


    public function fetch_data($params, $page, $perpage = 20){
        $where_params = "";
        $start = ((int)$page - 1) * $perpage;
        if(!empty($params)){
            $options = [];
        //$obj[] = [option_name, db_field_name, value]
            foreach($params as $field=>$value){
                if(!empty($value)) $options[] = [$field, $field, $value];
            }
            $where_params = !empty($options) ? " WHERE ". implode(" AND ", $options) : ""; 
        }

        $query = "SELECT d.*, p.name as location, c.title as category, s.name as source FROM datasets d LEFT JOIN places 
        p ON d.location_id = p.id LEFT JOIN categories c ON d.category_id = c.id LEFT JOIN sources s ON d.source_id = s.id  {$where_params}  
        ORDER BY d.id DESC LIMIT {$perpage}  OFFSET {$start}";
        $result = $this->query($query);
        $data = [];
        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }

    public function fetch_places($page, $perpage =10){
        $start = ((int)$page - 1) * $perpage;

        $query = "SELECT * FROM places ORDER BY id DESC LIMIT {$perpage} OFFSET {$start}";
        $result = $this->query($query);
        $data = [];
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }

        return $data;
    }

    public function delete_entity($id, $table){
        $this->delete($id, $table);
    }

    public function fetch_categories($page, $perpage = 10){
        $start = ((int)$page - 1) * $perpage;
        $query = "SELECT * FROM categories ORDER BY id DESC LIMIT {$perpage} OFFSET {$start}";
        $result = $this->query($query);
        $data = [];
        if(mysqli_num_rows($result) > 0 ){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }

        return $data;
    }
    //Put or Insert functions
    public function put_data($ins){
        return $this->insert($ins, "datasets");
    }
    public function put_place($ins){
        $ins["date_updated"] = date('Y-m-d H:i:s');
        return $this->insert($ins, "places");
    }
    public function put_category($ins){
        $ins["date_updated"] = date('Y-m-d H:i:s');
        return $this->insert($ins, "categories");
    }

    //Update or Edit functions

    public function updatedata($id, $ins){
        return $this->update($id, $ins, "datasets");
    }

    public function updateplace($id, $ins){
        $ins["date_updated"] = date("Y-m-d H:i:s");
        return $this->update($id, $ins, "places");
    }

    public function update_category($id, $ins){
        $ins["date_updated"] = date("Y-m-d H:i:s");
        return $this->update($id, $ins, "categories");
    }
    
}


?>