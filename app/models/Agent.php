<?php

class Agent extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "agents");
    }

    public function all() {
        $this->load();
    }

    public function getById(int $id) {
        $this->load(array("agentID=?", $id));
    }

    public function getByIdUser(int $id) {
        $this->load(array("userID=?", $id));
    }

    public function edit($nom_guichet) {
        $this->nom_guichet = $nom_guichet;
        $this->save();
    }

    public function deleteByUserId($id) {
        $this->load(array("userID=?", $id));
        $this->erase();
    }
    public function addByIdUser($id, $nom_guichet) {
        $this->reset();
        $this->userID = $id;
        $this->nom_guichet = $nom_guichet;
        $this->save();
    }
}