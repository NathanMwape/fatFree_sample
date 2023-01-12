<?php
class User extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "users");
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function add() {
        $this->reset();
        $this->copyfrom("POST");
        $this->save();
    }

    public function getById(int $id) {
        $this->load(array("userID=?", $id));
    }

    public function getByIdUser(int $id) {
        $this->load(array("userID=?", $id));
    }
    
    public function edit($params) {
        if(!$this->dry()) {
            $this->nom = $params["nom"];
            $this->postnom      = $params["postnom"];
            $this->prenom       = $params["prenom"];
            $this->adresse      = $params["adresse"];
            $this->telephone    = $params["telephone"];
            $this->mot_de_passe = $params["mot_de_passe"];
            $this->role         = $params["role"];
            $this->save();
        }
    }

    public function delete(int $id=-1) {
        if($id == -1) {
            if(! $this->dry())
                $this->erase();
        
        }else {
            $this->load(array("userID=?", $id));
            $this->erase();
        }
    }

}