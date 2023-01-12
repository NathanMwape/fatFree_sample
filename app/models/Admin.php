<?php
class Admin extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "admins");
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function getById(int $id) {
        $this->load(array("adminID=?", $id));
    }

    public function getByIdUser(int $id) {
        $this->load(array("userID=?", $id));
    }

    public function edit() {}

    public function deleteByUserId($id) {
        $this->load(array("userID=?", $id));
        $this->erase();
    }
    public function addByIdUser($id) {
        $this->reset();
        $this->userID = $id;
        $this->save();
    }
}