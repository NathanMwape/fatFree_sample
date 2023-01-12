
var contenaire = document.getElementById("not_found");
var flag = true;
setInterval(function() {
    
    if(flag) {
        contenaire.style.backgroundColor = 'red';
        contenaire.style.color = 'white';
        flag = false;
    }
    else {
        contenaire.style.backgroundColor = 'white';
        contenaire.style.color = 'red';
        flag = true;    
    }
}, 500);
