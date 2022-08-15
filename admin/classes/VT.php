<?php

class VT extends Upload
{

    const HOST = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    const DATABASE = "adminpanel";

    protected static $connection; // static yaptık ki self ögesi altında çağırılabilsin...

    public static $table;
    public static $select = "*";
    public static $whereRawKey;
    public static $whereRawVals;
    public static $whereKey;
    public static $whereVals = [];
    public static $operator;
    public static $parameter;
    public static $orderBy = null;
    public static $limit = null;
    public static $join = "";
    public static $leftJoin = "";



    function __construct()
    {
        self::__connect();
    }

    public static function __connect()
    {
        try {

            self::$connection = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::DATABASE . ";charset=utf8", self::USERNAME, self::PASSWORD);
        } catch (PDOException $error) {
            $data = (object) [
                "type" => "501",
                "title" => "Bağlantı hatası!",
                "message" => "DB bağlantısı sağlanamadı",
                "code" => $error->getMessage()
            ];

            include_once __DIR__ . "./../../errors/connection.php";
            exit();
        }
    }

    /**
     * SQL de seçilecek tablo yu belirler
     *
     * @param string $table
     * @return void
     */
    public static function table($table)
    {
        self::$table = $table;
        self::$select = "*";
        self::$whereRawKey = null;
        self::$whereRawVals = null;
        self::$whereKey = null;
        self::$whereVals = [];
        self::$operator = null;
        self::$parameter = null;
        self::$orderBy = null;
        self::$limit = null;
        self::$join = "";
        self::$leftJoin = "";
       
        return new self;
    }

    /**
     * Undocumented function
     *
     * @param string|array $select
     * @return void
     */
    public static function select($select) :VT
    {
        self::$select = is_array($select) ? implode(",", $select) : $select;

        return new self;
    }

    /**
     * elle yazılmış where sql koşullarını işler
     *
     * @param string $whereRawKeys
     * @param array $whereRawVals
     * @return void
     */
    public static function whereRaw(string $whereRawKey, array $whereRawVals)  :VT
    {
        self::$whereRawKey = "(" . $whereRawKey . ")";
        self::$whereRawVals = $whereRawVals;
        return new self;
    }

    /**
     * Undocumented function
     *
     * @param string|array $conditions
     * @param string|null $operator
     * @param string|null $parameter
     * @return void
     */
    public static function where($conditions, $operator=null, $parameter=null) :VT
    {
        if (is_array($conditions)) {
            $keyList = []; 
            foreach ($conditions as $key => $value) {
                self::$whereVals[] = $value;
                $keyList[] = $key;
            }
            self::$whereKey = implode("=? AND ", $keyList) . "=? ";
        } 
        else if ($operator != null && $parameter == null) 
        {
            self::$whereVals[]=$operator;
            self::$whereKey = $conditions . "=? ";

        }
        else if($parameter != null)
        {
            self::$whereVals[] = $parameter;
            self::$whereKey = $conditions . $operator . "? ";
        }

        return new self;
    }

    /**
     * sql sorgusunda sıralama kriterini girmeye yarar
     *
     * @param array $orderParameters
     * @return void
     */
    public static function orderBy(array $orderParameters) :VT
    {
        self::$orderBy = $orderParameters[0] . " " . (!empty($orderParameters[1]) ? $orderParameters[1] : "ASC");
        return new self;
    }

    /**
     * sql sorgusunda limit girme işlemi yapar
     *
     * @param integer $start
     * @param integer|null $end
     * @return void
     */
    public static function limit(int $start, int $end = null) :VT
    {
        self::$limit = $start .($end != null ? ",".$end : null);
        return new self;
    }

    public static function join($tableName, $thisColumn, $joinColumn) :VT
    {
        self::$join .= " INNER JOIN " . $tableName . " ON " . self::$table . "." . $thisColumn . " = " . $tableName . "." . $joinColumn . " ";
        return new self;
    }

    public static function leftJoin($tableName, $thisColumn, $joinColumn) :VT
    {
        self::$leftJoin .= " LEFT JOIN " . $tableName . " ON " . self::$table . "." . $thisColumn . " = " . $tableName . "." . $joinColumn . " ";
        return new self;
    }
}
