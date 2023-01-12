<?php

class OfficierController extends BaseController {

    public function authenticate() {
        
        $password = $this->f3->get("POST.password");
        $login = $this->f3->get("POST.login");
        if(isset($password) && isset($login))
        {
            //Parcours tous les enregistrement dans la table Agent
            $agent_m = new Officier($this->db); //Mapper
            $agent_m->login = "SELECT prenom FROM users WHERE users.userID=officiers.userID";
            $agent_m ->password = "SELECT mot_de_passe FROM users WHERE users.userID=officiers.userID";
            $agent_m->all();
            while(! $agent_m->dry() )
            {
                if(strcmp($login, $agent_m->login) == 0 && strcmp($password, $agent_m->password) == 0)
                {
                    new Session();
                    $this->f3->set("SESSION.login", $login);
                    $this->f3->set("SESSION.password", $password);
                    $this->f3->reroute("/officier/accueil");
                    return;
                }
                $agent_m->next();    
            }
            
        }
        $this->f3->reroute("/officier?nothinghere");
    }
    
    public function accueil() {
        new Session();
        $login    = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password))
            echo Template::instance()->render("officier_scanner.html");
        else 
            echo Template::instance()->render("erreur_page.html");
    }
    
    public function identify_passager() {
        new Session();
        $login    = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        
        if(isset($login) && isset($password)) {

            $contenu_qrcode = $this->f3->get("PARAMS.contenu_qrcode");
            
            $passager = new Passager($this->db);
            $jeton = new Jeton($this->db);
            $auto = 'N';

            $passager->getByQrcode($contenu_qrcode);
            if($passager->dry())
                $passager = FALSE;
            else if( strcmp($passager->sortie, "Non") == 0)
                $auto = 'Non';    
            else 
                $jeton->getByIdPassager($passager->passagerID);
            
            $this->f3->set("auto", $auto);
            $this->f3->set("passager", $passager);
            $this->f3->set("jeton", $jeton);
            echo Template::instance()->render("officier_jeton.html");
        
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    public function conf_sortie() {
        new Session();
        $login    = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password)) {
            $id = $this->f3->get("PARAMS.id");
            $passager = new Passager($this->db);
            $passager->getById($id);
            $passager->dej_sortie = "Oui"; 
            $passager->save();
            echo Template::instance()->render("officier_scanner.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");        
    }
}
