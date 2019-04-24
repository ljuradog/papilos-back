<?php

class PDOConfig{
    private static $instance;
    private $host = 'ec2-54-83-61-142.compute-1.amazonaws.com';
    private $db_name = 'd5idkej4f2g92m';
    private $user = 'msxppimugpfbvl';
    private $pass = '9e1319586dcaaa7f1210851a9a9986a29732797c72ba64256d67972d50d92c23';
    private $assoc = PDO::FETCH_ASSOC;
    
    private function __construct(){}
    
    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new PDOConfig();
        }
        return self::$instance;
    }
    public function getDsn(){
       //return 'mysql:host=' . $this->host . ';port=3306;dbname=' . $this->db_name . ';charset=utf8';
        return 'pgsql:host=' . $this->host . ';port=5432;dbname=' . $this->db_name . ';charset=utf8';
    }
    public function getUser(){
        return $this->user;
    }
    public function getPass(){
        return $this->pass;
    }
    public function getAssoc(){
        return $this->assoc;
    }
    public function setHost($host){
        $this->host = $host;
    }
    public function setDbName($db_name){
        $this->db_name = $db_name;
    }
    public function setUser($user){
        $this->user = $user;
    }
    public function setPass($pass){
        $this->pass = $pass;
    }
    public function setAssoc($assoc){
        $this->assoc = $assoc;
    }
}
class DBDriver{
    private $pdo;
    private $last_id = -1;
    private $config;
    public function __construct(PDOConfig $pdo_conf, $ubicacion=''){
        
        /*switch ($ubicacion) { 
            case "online-pruebas":
                $pdo_conf->setHost('192.168.10.7'); 
                $pdo_conf->setDbName('base_olimpo');  
                $pdo_conf->setUser('Leonardo');     
                $pdo_conf->setPass('Fy4jWOjZSeYk');
                break;
            default:
                break;
        }*/
        
        //$this->pdo = new PDO($pdo_conf->getDsn(), $pdo_conf->getUser(), $pdo_conf->getPass()); // MySQL
        //$this->pdo = new PDO($pdo_conf->getDsn().';'.$pdo_conf->getUser().';'.$pdo_conf->getPass());
        $this->pdo = new PDO('pgsql:dbname=d5idkej4f2g92m;host=ec2-54-83-61-142.compute-1.amazonaws.com;user=msxppimugpfbvl;password=9e1319586dcaaa7f1210851a9a9986a29732797c72ba64256d67972d50d92c23');
        $this->config = $pdo_conf;
    }
    public function query($query){
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->query($query);
            if ($stmt) {
                $fetch = $stmt->fetchAll($this->config->getAssoc());
                $this->last_id = $this->pdo->lastInsertId();
            } else {
                $this->pdo->rollBack();
                $this->last_id = -1;
                return false;
            }
            $this->pdo->commit();
        } catch (PDOException $ex) {
            throw $ex;
        }
        return $fetch;
    }
    public function set($query,$exec,$seqName = ''){
        $stmt1 = $this->pdo->prepare($query);
        try {
            $this->pdo->beginTransaction();
            $stmt1->execute($exec);
            if ($stmt1) {
                $fetch = $stmt1->fetchAll($this->config->getAssoc());
                $this->last_id = $this->pdo->lastInsertId($seqName);
            } else {
                $this->pdo->rollBack();
                $this->last_id = -1;
                return false;
            }
            $this->pdo->commit();
        } catch (PDOException $ex) {
            error_log("error ". print_r($ex, true));
            throw $ex;
        }
        return $fetch;
    }
    public function getLastId(){
        return $this->last_id;
    }
}