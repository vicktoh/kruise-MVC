<?php
class Db extends Model{

    function __construct(){
        parent::__construct();
    }

    public function createDatabases(){
        // tags table
        $query = "CREATE TABLE IF NOT EXISTS tags (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
        , name VARCHAR(200), 
        date_updated DATETIME )";
        $result = $this->query($query);
        echo "Created Table tags <br>";

        //places table
        $query = "CREATE TABLE IF NOT EXISTS places (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(200) NOT NULL, country VARCHAR(200), lat DECIMAL(10,8) NOT NULL, lng DECIMAL(11,8), 
         date_updated DATETIME )";
         $result = $this->query($query);
         echo "Created Table places <br>";

        //categories table 
        $query = "CREATE TABLE IF NOT EXISTS categories (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title TEXT, date_updated DATETIME )";
        $result = $this->query($query);
        echo "Created Table categories <br>";

        //sources table
        $query = "CREATE TABLE IF NOT EXISTS sources (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(200) NOT NULL, source_link VARCHAR(200) )";
        $result = $this->query($query);
        echo "Created Table sources <br>";

        //datasets table
        $query = "CREATE TABLE IF NOT EXISTS datasets (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        title TEXT, description TEXT, location_id INT(11), category_id INT(11), file_type VARCHAR(200), data_url VARCHAR(300),
        source_id INT(11), date_updated DATETIME, updated_by INT(11),
         FOREIGN KEY(location_id) REFERENCES places(id) ON UPDATE CASCADE ON DELETE SET NULL,
         FOREIGN KEY(category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE SET NULL, 
         FOREIGN KEY(source_id) REFERENCES sources(id) ON UPDATE CASCADE ON DELETE SET NULL )";
        $result = $this->query($query);
        echo "Created Table datasets <br>";

        //data_tags table table

        $query = "CREATE TABLE IF NOT EXISTS data_tags (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, tag_id INT(11) NOT NULL, 
        data_id INT(11), FOREIGN KEY (tag_id) REFERENCES tags(id) ON UPDATE CASCADE ON DELETE CASCADE, 
        FOREIGN KEY(data_id) REFERENCES datasets(id) ON UPDATE CASCADE ON DELETE CASCADE ) ";
        $result = $this->query($query);
        echo "Created Table data_tags <br>"; 
        
        $query = "CREATE TABLE IF NOT EXISTS dt_users (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, auth_id INT(10) UNSIGNED NOT NULL, 
        name VARCHAR(100) NOT NULL, phone VARCHAR(15), bio MEDIUMTEXT, photo VARCHAR(255) ) ";
        $result = $this->query($query);
        
    }



}


?>