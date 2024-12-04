<?php
    if(strpos($_SERVER['REQUEST_URI'],"pages")){
        exit(header("Location:../index.php"));
    }
    
    $namars       = getOne("select setting.nama_instansi from setting");
    $nosurat      = "";
    $norawat      = "";
    
    $_sql         = "select * from antripersetujuanumum" ;  
    $hasil        = bukaquery2($_sql);
    while ($data = mysqli_fetch_array ($hasil)){
        $nosurat  = $data['no_surat'];
        $norawat  = $data['no_rawat'];
    }
    
    $no_rkm_medis = "";
    $nm_pasien    = "";
    $jk           = "";
    $umur         = "";
    $tgl_lahir    = "";
    $alamat       = "";
    $no_tlp       = "";
    
    $_sql2  = "select reg_periksa.no_rawat,pasien.no_rkm_medis,pasien.nm_pasien,if(pasien.jk='L','LAKI-LAKI','PEREMPUAN') as jk,
               pasien.umur,DATE_FORMAT(pasien.tgl_lahir,'%d-%m-%Y') as tgl_lahir,concat(pasien.alamat,', ',kelurahan.nm_kel,', ',kecamatan.nm_kec,', ',kabupaten.nm_kab) as alamat, 
               pasien.no_tlp from reg_periksa inner join pasien on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
               inner join kelurahan on pasien.kd_kel=kelurahan.kd_kel
               inner join kecamatan on pasien.kd_kec=kecamatan.kd_kec 
               inner join kabupaten on pasien.kd_kab=kabupaten.kd_kab
               where reg_periksa.no_rawat='".$norawat."'" ;  
    $hasil2 = bukaquery2($_sql2);
    while ($data2  = mysqli_fetch_array ($hasil2)){
        $no_rkm_medis = $data2['no_rkm_medis'];
        $nm_pasien    = $data2['nm_pasien'];
        $jk           = $data2['jk'];
        $umur         = $data2['umur'];
        $tgl_lahir    = $data2['tgl_lahir'];
        $alamat       = $data2['alamat'];
        $no_tlp       = $data2['no_tlp'];
    }
    
    $tanggal        = "";
    $nama_pj        = "";
    $umur_pj        = "";
    $no_ktppj       = "";
    $jkpj           = "";
    $bertindak_atas = "";
    $no_telp        = "";
    $_sql2  = "select DATE_FORMAT(surat_persetujuan_umum.tanggal,'%d-%m-%Y') as tanggal,surat_persetujuan_umum.nama_pj,surat_persetujuan_umum.umur_pj,
               surat_persetujuan_umum.no_ktppj,if(surat_persetujuan_umum.jkpj='L','LAKI-LAKI','PEREMPUAN') as jkpj,surat_persetujuan_umum.bertindak_atas,
               surat_persetujuan_umum.no_telp from surat_persetujuan_umum where surat_persetujuan_umum.no_surat='$nosurat'" ;  
    $hasil2 = bukaquery2($_sql2);
    while ($data2  = mysqli_fetch_array ($hasil2)){
        $tanggal        = $data2['tanggal'];
        $nama_pj        = $data2['nama_pj'];
        $umur_pj        = $data2['umur_pj'];
        $no_ktppj       = $data2['no_ktppj'];
        $jkpj           = $data2['jkpj'];
        $bertindak_atas = $data2['bertindak_atas'];
        $no_telp        = $data2['no_telp'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIMKES Khanza</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <style>
        .signature-pad {
            border: 1px solid #000;
            width: 100%;
            height: 300px;
        }
        #results {
            padding: 10px;
            background: #EEFFEE;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h5 class="text-dark">
            <center>
                <button class="btn btn-secondary" onclick="window.location.reload();">Refresh</button>
                <br/><br/>
                KETENTUAN RAWAT INAP DAN PERSETUJUAN UMUM (GENERAL CONSENT)<br>NO. <?=$nosurat;?>
            </center>
        </h5>
        <h7 class="text-dark"><center>Tanggal <?=$tanggal;?></center></h7><br/>
        
        <form method="POST" action="pages/storeImage.php" onsubmit="return validasiIsi();" enctype="multipart/form-data">
            <input type="hidden" name="nosurat" value="<?=$nosurat;?>">
            <input type="hidden" name="image" class="image-tag">
            
            <h7 class="text-dark"><b>TANDATANGAN DI SINI</b></h7>
            <canvas id="signaturePad" class="signature-pad"></canvas>
            <button type="button" id="clearBtn" class="btn btn-secondary">Clear</button>
            <br/>
           
            <h7 class="text-dark"><center>Yang Membuat Pernyataan</center></h7>
            <div class="row">
                <div class="col-md-12">
                    <div id="results">
                        <h7 class="text-success">Gambar akan diambil jika Anda sudah mengeklik tombol "Ya"</h7>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <br>
                    <input type="button" class="btn btn-warning" value="Ya, Saya sebagai pembuat pernyataan" id="saveBtn">
                    <button type="submit" class="btn btn-danger">Simpan</button>
                    
                </div>
            </div>
        </form>
    </div>
    <script>
 // Canvas setup
var canvas = document.getElementById("signaturePad");
var ctx = canvas.getContext("2d");
var isDrawing = false;

// Set canvas size
canvas.width = 990;
canvas.height = 300;

// Variables to track drawing bounds
let minX = canvas.width, minY = canvas.height, maxX = 0, maxY = 0;

// Event listeners for mouse
canvas.addEventListener("mousedown", startDrawing);
canvas.addEventListener("mousemove", draw);
canvas.addEventListener("mouseup", stopDrawing);
canvas.addEventListener("mouseleave", stopDrawing);

// Event listeners for touch
canvas.addEventListener("touchstart", startDrawingTouch);
canvas.addEventListener("touchmove", drawTouch);
canvas.addEventListener("touchend", stopDrawing);

// Functions for mouse events
function startDrawing(e) {
    isDrawing = true;
    draw(e);
}

function draw(e) {
    if (!isDrawing) return;

    // Get cursor position relative to canvas
    const x = e.clientX - canvas.offsetLeft;
    const y = e.clientY - canvas.offsetTop;

    // Update bounds
    minX = Math.min(minX, x);
    minY = Math.min(minY, y);
    maxX = Math.max(maxX, x);
    maxY = Math.max(maxY, y);

    // Draw
    ctx.lineWidth = 3;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";

    ctx.lineTo(x, y);
    ctx.stroke();
}

// Functions for touch events
function startDrawingTouch(e) {
    e.preventDefault();
    isDrawing = true;
    const touch = e.touches[0];
    const x = touch.clientX - canvas.offsetLeft;
    const y = touch.clientY - canvas.offsetTop;
    ctx.moveTo(x, y);
}

function drawTouch(e) {
    if (!isDrawing) return;
    e.preventDefault();
    const touch = e.touches[0];
    const x = touch.clientX - canvas.offsetLeft;
    const y = touch.clientY - canvas.offsetTop;

    // Update bounds
    minX = Math.min(minX, x);
    minY = Math.min(minY, y);
    maxX = Math.max(maxX, x);
    maxY = Math.max(maxY, y);

    // Draw
    ctx.lineWidth = 3;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";

    ctx.lineTo(x, y);
    ctx.stroke();
}

// Stop drawing for both mouse and touch
function stopDrawing() {
    isDrawing = false;
    ctx.beginPath();
}

        // Clear the canvas
        document.getElementById("clearBtn").addEventListener("click", function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            minX = canvas.width;
            minY = canvas.height;
            maxX = 0;
            maxY = 0;
        });

        // Save canvas as cropped image
        document.getElementById("saveBtn").addEventListener("click", function() {
            // Crop the drawing area
            const croppedWidth = maxX - minX;
            const croppedHeight = maxY - minY;

            if (croppedWidth <= 0 || croppedHeight <= 0) {
                alert("Tanda tangan kosong. Silakan tanda tangan terlebih dahulu.");
                return;
            }

            // Create a temporary canvas for cropping
            const tempCanvas = document.createElement("canvas");
            tempCanvas.width = croppedWidth;
            tempCanvas.height = croppedHeight;
            const tempCtx = tempCanvas.getContext("2d");

            // Draw the cropped area
            tempCtx.drawImage(
                canvas,
                minX, minY, croppedWidth, croppedHeight, // Source
                0, 0, croppedWidth, croppedHeight       // Destination
            );

            // Get the cropped image as data URL
            const image = tempCanvas.toDataURL("image/png");
            document.querySelector(".image-tag").value = image;

            // Display the cropped image in the results div
            document.getElementById("results").innerHTML = 
                '<img src="' + image + '" alt="Signature" style="max-width: 100%; height: auto;"/>';
        });

        // Validate form
        function validasiIsi() {
            const image = document.querySelector(".image-tag").value;
            if (image === "") {
                alert("Silakan tanda tangan terlebih dahulu.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>


