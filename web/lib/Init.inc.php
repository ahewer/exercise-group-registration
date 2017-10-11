<?php
class Init{

    public static function output() {

        if( file_exists("./output") == false) {

            self::create_database_folder();
            self::create_htaccess_file();
            self::create_database_file();

        }

    }

    public static function create_database_folder() {

        mkdir("./output");

    }

    public static function create_htaccess_file() {

        file_put_contents("./output/.htaccess", "Require all denied");

    }

    public static function create_database_file() {

        $databaseFile = "./output/database.db";
        touch($databaseFile);
        chmod($databaseFile, 0777);

    }

}
?>