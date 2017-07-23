<?php

class Database extends SQLite3{

    /*-------------------------------------------------------------------------*/

    public function __construct() {

      $this->open("output/database.db");
      $this->busyTimeout(5000);
      $this->exec(
          "CREATE TABLE IF NOT EXISTS Registrations (id INTEGER primary key, data STRING)"
      );
      
    }

    /*-------------------------------------------------------------------------*/

}

?>
