<?php
class Passager extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "passagers");
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function add() {
        $this->reset();
        $this->copyfrom("POST");
        $path="jetons/";
        $this->contenu_qrcode = explode("/", $path.uniqid())[1];
        $this->save();
    }

    public function getByQrcode($cont_qrcode) {
        $this->load(array("contenu_qrcode=?", $cont_qrcode));
    }

    public function getById(int $id) {
        $this->load(array("passagerID=?", $id));
    }
    
    public function edit($id) {
        $this->getById($id);
        $this->copyfrom("POST");
        $this->update();
    }

    public function delete(int $id=-1) {
        if($id == -1) {
            if(! $this->dry())
                $this->erase();
        
        }else {
            $this->load(array("passagerID=?", $id));
            $this->erase();
        }
    }

}