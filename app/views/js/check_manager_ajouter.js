nom          = document.getElementById('nom');
postnom      = document.getElementById('postnom');
prenom       = document.getElementById('prenom');
mot_de_passe = document.getElementById('mot_de_passe');
adresse      = document.getElementById('adresse');
telephone    = document.getElementById('telephone');
function check() {
    if(nom.value == '' || postnom.value == '' || prenom.value == '' ||
       mot_de_passe.value == '' || adresse.value == '' || telephone.value == '')
    {
        alert("Veuillez remlir tous les champs");
        return false;
    }
    return true;
}