<?php


class Config extends PDO {
    protected $dsn = 'mysql:host=localhost;dbname=fatadgestao;charset=utf8';
    protected $user = 'root';
    //protected $password = "CruGuaMys1634*";
    protected $password = '';


    public function __construct() {
        try {
            parent::__construct($this->dsn, $this->user, $this->password);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro na conexão: ' . $e->getMessage();
            exit;
        }
    }
}