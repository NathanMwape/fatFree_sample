<?php

class Rapport extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "rapports");
    }

    public function all() {
        $this->load();
    }

    public function getById(int $id) {
        $this->load(array("rapportID=?", $id));
    }

    public function getByIdAgent(int $id) {
        $this->load(array("agentID=?", $id));
    }

    public function delete($id) {
        $this->load(array("rapportID=?", $id));
        $this->erase();
    }

    public function deleteByAgentId($id) {
        $this->load(array("agentID=?", $id));
        $this->erase();
    }

    public function add($rapport_content, $id_agent) {
        $this->contenu = $rapport_content;
        $this->agentID = $id_agent;
        $this->save();
    }
}