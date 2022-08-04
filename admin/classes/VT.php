<?php

class VT extends Upload {

    const HOST = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    const DATABASE = "adminpanel";

    protected static $connection; // static yaptık ki sellf ögesi altında çağırılabilsin...

    function __construct(){
        self::__connect();
    }

    public static function __connect(){
        try {
            
            self::$connection = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE."",self::USERNAME,self::PASSWORD);

        }catch (PDOException $error){
            $data =(Object) [
                "type" => "501",
                "title" => "Connection error!",
                "message" => "DB bağlantısı sağlanamadı",
                "code" => $error->getMessage()
            ];

            return $data;
            exit();
        }
    }



} 