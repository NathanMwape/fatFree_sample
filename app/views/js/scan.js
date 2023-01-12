
// Cree notre objet qui est cense scanner
var scanner = new Instascan.Scanner({video : document.getElementById("preview")});

//Recupere la ressource (Dans notre cas la camera)
Instascan.Camera.getCameras().then(function(cameras){
    if(cameras.length > 0) {
        scanner.start(cameras[0]);
        // Commence le scanne des que la camera est ouverte
        scanner.addListener('scan', function(c) {
            window.location.href = "/officier/identifier/".concat(c);
        });

    } else {
        alert("Pas de camera sur cette appareil");
    }
}).catch(function(e) {
    console.error(e);
});
