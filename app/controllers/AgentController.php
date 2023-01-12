<?php

class AgentController extends BaseController {

    public function authenticate() {
        
        $password = $this->f3->get("POST.password");
        $login = $this->f3->get("POST.login");
        if(isset($password) && isset($login))
        {
            //Parcours tous les enregistrement dans la table Agent
            $agent_m = new Agent($this->db); //Mapper
            $agent_m->login = "SELECT prenom FROM users WHERE users.userID=agents.userID";
            $agent_m ->password = "SELECT mot_de_passe FROM users WHERE users.userID=agents.userID";
            $agent_m->all();
            while(! $agent_m->dry() )
            {
                echo  $agent_m->password . " " . $agent_m->login . "<br>";
                if(strcmp($login, $agent_m->login) == 0 && strcmp($password, $agent_m->password) == 0)
                {
                    new Session();
                    $this->f3->set("SESSION.login", $login);
                    $this->f3->set("SESSION.password", $password);
                    $this->f3->set("SESSION.id_agent", $agent_m->agentID);
                    $this->f3->reroute("/agent/accueil");
                    return;
                }
                $agent_m->next();    
            }
            
        }
        $this->f3->reroute("/agent?nothinghere");
    }

    public function accueil() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password)) {
            $passagers = new Passager($this->db);
            $this->f3->set("passagers", $passagers->all());
            echo Template::instance()->render("agent_passager_list.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    # Affiche le formulaire d'ajout d'un passager
    public function add_passager() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        $this->f3->set('nom_agent', $this->f3->get("SESSION.login"));
        if(isset($login) && isset($password)) { 
            echo Template::instance()->render("agent_ajouter_passager.html");
        }
        else {
            echo Template::instance()->render("erreur_page.html");
        }
    }

    # Ajouter un passager dans la base de donnees
    # On cree par la meme occassion son jeton
    public function add_passager_proc() {
        $passager = new Passager($this->db);
        $jeton = new Jeton($this->db);
        
        $passager->add();
        $jeton->add($passager);
        
        $this->f3->reroute("/agent/accueil");
    }


    public function delete() {
        $id = $this->f3->get("PARAMS.id");
        $passager = new Passager($this->db);
        $jeton = new Jeton($this->db);
        $jeton->getByIdPassager($id);
        $jeton->delete();
        $passager->delete($id);
        $this->f3->reroute("/agent/accueil");
    }

    public function maj_form() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            $passager = new Passager($this->db);
            $passager->getById($this->f3->get("PARAMS.id"));
            $this->f3->set("passager", $passager);
            echo Template::instance()->render("agent_maj.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    public function passager_update_dbb() {
        $passager = new Passager($this->db);
        $passager->edit($this->f3->get("PARAMS.id"));
        $this->f3->reroute("/agent/accueil");
    }

    public function show_jeton() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            $id_passager = $this->f3->get("PARAMS.id");
            $passager = new Passager($this->db);
            $jeton = new Jeton($this->db);
            $passager->getById($id_passager);
            $jeton->getByIdPassager($id_passager);
            $this->f3->set("passager", $passager);
            $this->f3->set("jeton", $jeton);
            echo Template::instance()->render("agent_jeton.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    public function write_rapport_page() {
        new Session();
        $login    = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password)) 
            echo Template::instance()->render("agent_rapport.html");
        else 
            echo Template::instance()->render("erreur_page.html");    
    }

    public function add_rapport() {
        new Session();
        $login    = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password)) {
            $id_agent = $this->f3->get("SESSION.id_agent");
            $rapport = new Rapport($this->db);
            $rapport->add($this->f3->get("POST.rapport"), $id_agent);
            $this->f3->reroute("/agent/accueil");
        }
        else 
            echo Template::instance()->render("erreur_page.html");    
    }
}
