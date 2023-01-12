<?php
require_once "lib/phpqrcode/qrlib.php";

class Jeton extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, "jetons");
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function add($passager) {
        $this->reset();
        $nom_fichier = "data/jetons/" . $passager->contenu_qrcode . ".png";
        $this->nom_fichier = $nom_fichier;
        $pixel_size = 500;
        $frame_size = 2;
        QRcode::png($passager->contenu_qrcode, 
                    $nom_fichier, 
                    'L', 
                    $pixel_size, 
                    $frame_size
        );
        $this->passagerID = $passager->passagerID;
        $this->save();
    }

    public function getById(int $id) {
        $this->load(array("jetonID=?", $id));
    }

    public function getByIdPassager(int $id) {
        $this->load(array("passagerID=?", $id));
    }
    
    public function delete(int $id=-1) {
        if($id == -1) {
            if(! $this->dry()) {
                $this->erase();
                unlink($this->nom_fichier);
            }
        }else {
            $this->load(array("jetonID=?", $id));
            unlink($this->nom_fichier);
            $this->erase();
        }
    }

}