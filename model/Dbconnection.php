<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/***********************connecting to the database*********************************** */
class Dbconnection
{
    public function __construct()
    {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
        "driver"=>_driver_,
        "host"=>_host_,
        "database"=>_database_,
        "username"=>_username_,
        "password"=>_password_
]);
    $this->capsule->setAsGlobal();
    $this->capsule->bootEloquent();
    }

    public function getTableName($table)
    {
        return Capsule::table($table);
        
    }

}
