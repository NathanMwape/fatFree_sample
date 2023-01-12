<?php

class BaseController {
    protected $db, $f3;
    function __construct() {
        $f3 = Base::instance();
        $db = new DB\SQL(
                            $f3->get("DB_DNS") . $f3->get("DB_NAME"),
                            $f3->get("DB_USER"),
                            $f3->get("DB_PASSWORD")
        );
        $this->db = $db;
        $this->f3 = $f3;
    }

    public function authenticate_page() {
        new Session();
        $login = $this->f3->get("SESSION.login");
        $password = $this->f3->get("SESSION.password");
        if(isset($login) && isset($password))
        {
            //Si l'utilisateur rentre, je me deconnecte et je me redirige a la page d'accueil
            $type_cnx = $this->deconnection();    
            $this->f3->reroute($this->f3->get("PARAMS.user_type") . "/");
            return;
        }
        switch($this->f3->get("PARAMS.user_type"))
        {
            case "admin" :
                echo Template::instance()->render("cnx_admin.html");
                return;
            case "officier" :
                echo Template::instance()->render("cnx_officier.html");
                return;
                case "agent" :
                    echo Template::instance()->render("cnx_agent.html");
                    return;
        }
    }

    function beforeroute() {

    }
    function afterroute() {

    }

    function deconnection() {
        $this->f3->set("SESSION.login", NULL);
        $this->f3->set("SESSION.password", NULL);
        // Retourne le type de connexion servant a la redirection
    }
}
