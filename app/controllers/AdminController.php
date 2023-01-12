<?php

class AdminController extends BaseController {

    public function authenticate() {

        $password = $this->f3->get("POST.password");
        $login = $this->f3->get("POST.login");
        if(isset($password) && isset($login))
        {
            // Parcours tous les enregistrement dans la table admins
            $admin_m = new Admin($this->db); //Mapper
            $admin_m->login = "SELECT prenom FROM users WHERE users.userID=admins.userID";
            $admin_m ->password = "SELECT mot_de_passe FROM users WHERE users.userID=admins.userID";
            $admin_m->all();
            while(! $admin_m->dry() )
            {
                if(strcmp($login, $admin_m->login) == 0 && strcmp($password, $admin_m->password) == 0)
                {
                    new Session();
                    $this->f3->set("SESSION.login", $login);
                    $this->f3->set("SESSION.password", $password);
                    $this->f3->reroute("/admin/accueil");
                    return;
                }
                $admin_m->next();    
            }
            
        }
        // nothinghere est juste une valeur qui nous permet de controler si
        // l'utilisateur a entrer des mauvaises informations
        $this->f3->reroute("/admin?nothinghere");
    }


    // Page d'accueil de l'administrateur
    public function accueil() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            /* Je peu soit utiliser trois variable pour les administrateurs,
               les officiers ainsi que les agents */
            $users = new User($this->db);
            $this->f3->set("users", $users->all());
            echo Template::instance()->render("manager.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }


    // Suppression d'un utilisateur du systeme
    public function delete() {
        $id = $this->f3->get("PARAMS.id");
        if(isset($id))
        {

            $user = new User($this->db);
            $user->getById($id);
            switch($user->role) {
                case 'Administrateur' :
                    $sub_user = new Admin($this->db);
                    break;
                case 'Officier' :
                    $sub_user = new Officier($this->db);
                    break;
                case 'Agent' :
                    $sub_user = new Agent($this->db);
                    break;
            }
            if(isset($sub_user)) {
                $sub_user->deleteByUserId($id);
                $user->delete();
            }
            $this->f3->reroute("admin/accueil");
        }
    }

    // Affiche le formulaire d'ajout d'un utilisateur dans la base de donnees
    public function add_user_form() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            echo Template::instance()->render("manager_ajouter_utilisateur.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");        
    }

    // Ajoute un utilisateur dans la base de donnees
    public function add_user() {

        $role    = $this->f3->get("POST.role"); 
        $guichet = $this->f3->get("POST.guichet");
        /* 
           ! La verification de ce formulaire doit se faire en javascript
        */
        $user = new User($this->db);
        $user->add();
        
        $sub_user = $this->getSubUserType($role);
    
        if( $sub_user instanceof Agent) 
            $sub_user->addByIdUser($user->userID, $guichet);
        else $sub_user->addByIdUser($user->userID);
        
        $this->f3->reroute("admin/accueil");
    }

    public function getSubUserType(string $role) {
        switch($role) {
            case "Agent" :
                return new Agent($this->db);
            case "Officier" :
                return new Officier($this->db);
            case "Administrateur" :
                return new Admin($this->db);
        }
    }

    public function maj_form() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            $user = new User($this->db);
            $user->getById($this->f3->get("PARAMS.id"));
            $this->f3->set("user", $user);
            $sub_user = $this->getSubUserType($user->role);
            $sub_user->getByIdUser($user->userID);
            $this->f3->set("sub_user", $sub_user);
            //Affiche le formulaire de mise a jour avec les information de l'utilisateur
            echo Template::instance()->render("manager_maj.html");    
        }
        else 
            echo Template::instance()->render("erreur_page.html");    
    }

    public function maj_proc() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            $user = new User($this->db);
            $user->getById($this->f3->get("PARAMS.id"));
            $sub_user = $this->getSubUserType($user->role);

            $params = array("nom"          => $this->f3->get("GET.nom"),
                        "postnom"      => $this->f3->get("GET.postnom"),
                        "prenom"       => $this->f3->get("GET.prenom"),
                        "adresse"      => $this->f3->get("GET.adresse"),
                        "telephone"    => $this->f3->get("GET.telephone"),
                        "mot_de_passe" => $this->f3->get("GET.mot_de_passe"),
                        "role"         => $user->role
            );

            $user->edit($params);
            if ($sub_user instanceof Agent) {
                $sub_user->getByIdUser($user->userID);
                $sub_user->edit($this->f3->get("GET.guichet"));
            }
            $users = new User($this->db);
            $this->f3->set("users", $users->all());
            echo Template::instance()->render("manager.html"); 
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    public function tab() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        # Si l'utilisateur est connecter on lui affiche sont 
        # page sinon on lui dit de se connecter
        if(isset($login) && isset($password))
        {
            $passager = new Passager($this->db);
            $passager->all();
            $stat = new Statistique();
            while(! $passager->dry() ) {
                if ($passager->genre == "M"){
                    switch($passager->nom_guichet){
                        case 'Guichet 1' :
                            $stat->h_g1++;
                            break;
                        case 'Guichet 2' :
                            $stat->h_g2++;
                            break;
                        case 'Guichet 3' :
                            $stat->h_g3++;
                            break;
                    }
                }else {
                    switch($passager->nom_guichet){
                        case 'Guichet 1' :
                            $stat->f_g1++;
                            break;
                        case 'Guichet 2' :
                            $stat->f_g2++;
                            break;
                        case 'Guichet 3' :
                            $stat->f_g3++;
                            break;
                    }
                }
                $passager->next();
            }
            $this->f3->set('stat', $stat);
            echo Template::instance()->render("manager_tableau.html");
        }
        else 
            echo Template::instance()->render("erreur_page.html");
    }

    public function show_rapports() {
            new Session();
            $login = $this->f3->get("SESSION.login");
            $password = $this->f3->get("SESSION.password");
            # Si l'utilisateur est connecter on lui affiche sont 
            # page sinon on lui dit de se connecter
            if(isset($login) && isset($password))
            {
                $rapport = new Rapport($this->db);
                $agent = new Agent($this->db);
                $agent->nom = "SELECT nom FROM users WHERE users.userID=agents.userID";
                $agent->prenom = "SELECT prenom FROM users WHERE users.userID=agents.userID";
                $agent->all();
                $rapport->all();
                $rapports = [];
                while(! $rapport->dry() ) {
                    if($rapport->agentID == $agent->agentID){
                        $contenu = explode("<p>", $rapport->contenu)[1];
                        $contenu = explode("</p>", $contenu)[0];
                        $rapports[] = ["nom"     => $agent->nom, 
                                       "prenom"  => $agent->prenom, 
                                       "contenu" => $contenu
                        ];
                    }
                    $rapport->next();
                }

                $this->f3->set("rapports", $rapports);
                echo Template::instance()->render("manager_rapports_list.html");
            }
            else 
                echo Template::instance()->render("erreur_page.html");      

    }
}
 