<?php

class VT extends Upload {

    const HOST = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    const DATABASE = "adminpanel";

    protected static $connection; // static yaptık ki self ögesi altında çağırılabilsin...

    public static $table;
    public static $select = "*";
    public static $whereRawKey;
    public static $whereRawVals;
    public static $conditions;
    public static $parameter;
    public static $operator;

    function __construct()
    {
        self::__connect();
    }

    public static function __connect()
    {
        try {
            
            self::$connection = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=utf8",self::USERNAME,self::PASSWORD);

        }catch (PDOException $error){
            $data =(Object) [
                "type" => "501",
                "title" => "Bağlantı hatası!",
                "message" => "DB bağlantısı sağlanamadı",
                "code" => $error->getMessage()
            ];

            include_once __DIR__. "./../../errors/connection.php";
            exit();
        }
    }

    /**
     * SQL de seçilecek tablo yu belirler
     *
     * @param string $table
     * @return void
     */
    public static function table ($table)
    {
        self::$table = $table;
        self::$select = "*";
        self::$whereRawKey = null;
        self::$whereRawVals = null;
        self::$conditions = null;
        self::$parameter = null;
        self::$operator = null;

        return new self;
    }

    /**
     * Undocumented function
     *
     * @param string|array $select
     * @return void
     */
    public static function select (string|array $select)
    {
        self::$select = is_array($select) ? implode(",", $select) : $select ;

        return new self;
    }

    /**
     * elle yazılmış where sql koşullarını işler
     *
     * @param string $whereRawKeys
     * @param array $whereRawVals
     * @return void
     */
    public static function whereRaw (string $whereRawKey, array $whereRawVals)
    {
        self::$whereRawKey = "(" . $whereRawKey . ")";
        self::$whereRawVals = $whereRawVals;
        return new self;

    }

    public static function where (string|array $conditions, ?string $operator, ?string $parameter) 
    {
        self::$conditions = $conditions;
        self::$parameter = $operator;
        self::$parameter = $parameter;
        return new self;
    }

} 