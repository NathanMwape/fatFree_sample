nom          = document.getElementById('nom');
postnom      = document.getElementById('postnom');
prenom       = document.getElementById('prenom');
genre_f      = document.getElementById('genre_f');
genre_m      = document.getElementById('genre_m');
age          = document.getElementById('age');
nat          = document.getElementById('nat');
adr          = document.getElementById('adr');
motif        = document.getElementById("motif");
sortie_oui   = document.getElementById('sortie_oui');
sortie_non   = document.getElementById('sortie_non');

function check() {
    cond_genre = genre_f.checked == false && genre_m.checked == false;
    cond_sortie = sortie_oui.checked == false && sortie_non.checked == false;
    if( nom.value == '' || postnom.value == '' || prenom.value == '' ||
        cond_genre || age.value == '' || nat.value == '' || 
        adr.value == '' || motif.value == '' || cond_sortie)
    {
        alert("Veuillez remlir tous les champs");
        return false;
    }
    else if(isNaN(age.value) == true)
    {
        alert('Age : doit etre un nombre.');
        return false;
    }
    return true;
}