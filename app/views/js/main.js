
opts = {
    video : document.getElementById("vid"),
    continous : true
}
let scanner = new Instascan.Scanner(opts);

scanner.addListener('scan', function(content) {
    document.getElementById("scannedValue").value  = content;
});

Instascan.Camera.getCameras().then(function(cameras) {
    if(cameras.length > 0) {
        scanner.start(cameras[0]);
    }
    else {
        alert("Pas de camera");
    }
}).catch(function(err) {
    alert(err);
})

