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
    <link rel="stylesheet" href="css/modal.css" />
    <link rel="stylesheet" href="css/csssurat.css" />
    <style>
    .signature-pad {
        border: 1px solid #000;
        width: 100%;
        height: auto;
        max-height: 300px; /* Batas maksimum tinggi */
    }

    @media (max-width: 768px) {
        .signature-pad {
            height: 200px; /* Kurangi tinggi pada layar kecil */
        }
    }

    @media (max-width: 480px) {
        .signature-pad {
            height: 150px; /* Lebih kecil untuk layar sangat kecil */
        }
    }

    #results {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            text-align: center;
            /* width: 100%;
            max-width: 600px; */
        }
}
</style>

</head>
<body>

    <div class="container">
    <div class="header">
        <!-- Logo -->
        <img src="../logo.png" alt="Logo Lampung" class="logo">
        <!-- Nomor RM -->
        <div class="rm-number">NO. <?=$nosurat;?></div>
        <!-- Teks Utama -->
        <div >
            <h2><?=$namars?></h2>
            <p>Jl. Pramuka No. 88 Rajabasa Bandar Lampung</p>
            <p>Telp. (0721) 706402 Fax. (0721) 706402</p>
        </div>
    </div>
    <!-- Garis Horizontal -->
    <div class="divider"></div>
        <h5 class="text-dark">
            <center>
                <button class="btn btn-secondary" onclick="window.location.reload();">Refresh</button>
                <br/><br/>
                KETENTUAN RAWAT INAP DAN PERSETUJUAN UMUM (GENERAL CONSENT)<br>
            </center>
        </h5>
        <h7 class="text-dark"><center>Tanggal <?=$tanggal;?></center></h7><br/>
        
        <form method="POST" action="pages/storeImage.php" onsubmit="return validasiIsi();" enctype="multipart/form-data">
            <input type="hidden" name="nosurat" value="<?=$nosurat;?>">
            <input type="hidden" name="image" class="image-tag">
            
            <h7 class="text-dark"><b>TANDATANGAN DI SINI</b></h7>
            <canvas id="signaturePad" class="signature-pad"></canvas><br>
            <button type="button" id="clearBtn" class="btn btn-secondary">Ulangi Tanda Tangan</button><br>
            <br/>
           <!-- Button trigger modal -->
    <!-- Tombol untuk Membuka Modal -->
        <!-- Tombol untuk Membuka Modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
       BACA PERSETUJUAN UMUM
    </button>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- modal-xl digunakan di sini -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">#####</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <?php include "data.php"; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            
                </div>
            </div>
        </div>
    </div>
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
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <!-- Bootstrap JS v4.1.3 -->
    <script src="js/bootstrap.min.js"></script>
    <script>
// Inisialisasi canvas
const canvas = document.getElementById("signaturePad");
const ctx = canvas.getContext("2d");
let isDrawing = false;

// Variabel untuk melacak area gambar
let minX, minY, maxX, maxY;

// Fungsi untuk merespons perubahan ukuran layar
function resizeCanvas() {
    // Simpan gambar sementara
    const tempCanvas = document.createElement("canvas");
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    tempCanvas.getContext("2d").drawImage(canvas, 0, 0);

    // Atur ulang ukuran canvas sesuai elemen induk
    canvas.width = canvas.parentElement.offsetWidth;
    canvas.height = canvas.offsetHeight || 300;

    // Salin kembali gambar
    ctx.drawImage(tempCanvas, 0, 0, canvas.width, canvas.height);

    // Reset koordinat batas
    minX = canvas.width;
    minY = canvas.height;
    maxX = 0;
    maxY = 0;
}

// Fungsi untuk mendapatkan posisi kursor (mouse/touch) relatif terhadap canvas
function getPos(event) {
    const rect = canvas.getBoundingClientRect();
    const clientX = event.touches ? event.touches[0].clientX : event.clientX;
    const clientY = event.touches ? event.touches[0].clientY : event.clientY;

    return {
        x: (clientX - rect.left) * (canvas.width / rect.width),
        y: (clientY - rect.top) * (canvas.height / rect.height),
    };
}

// Mulai menggambar
function startDrawing(event) {
    isDrawing = true;
    const pos = getPos(event);
    ctx.moveTo(pos.x, pos.y);

    // Perbarui batas awal
    minX = Math.min(minX, pos.x);
    minY = Math.min(minY, pos.y);
}

// Menggambar
function draw(event) {
    if (!isDrawing) return;
    const pos = getPos(event);

    // Perbarui batas area gambar
    minX = Math.min(minX, pos.x);
    minY = Math.min(minY, pos.y);
    maxX = Math.max(maxX, pos.x);
    maxY = Math.max(maxY, pos.y);

    // Gambar garis
    ctx.lineWidth = 1; // Ketebalan garis yang lebih tipis
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
}

// Berhenti menggambar
function stopDrawing() {
    isDrawing = false;
    ctx.beginPath();
}

// Fungsi untuk menghapus canvas
function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    minX = canvas.width;
    minY = canvas.height;
    maxX = 0;
    maxY = 0;
}

// Fungsi untuk menyimpan gambar tanda tangan
function saveCanvas() {
    if (maxX - minX <= 0 || maxY - minY <= 0) {
        alert("Tanda tangan kosong. Silakan tanda tangan terlebih dahulu.");
        return;
    }

    // Tambahkan padding agar gambar tidak terpotong
    const padding = 10; // Pixel tambahan di setiap sisi
    const croppedWidth = maxX - minX + padding * 2;
    const croppedHeight = maxY - minY + padding * 2;

    // Tentukan skala resolusi tinggi (contoh: 3x lebih besar)
    const scale = 3;

    // Buat canvas sementara untuk cropping dengan resolusi tinggi
    const tempCanvas = document.createElement("canvas");
    tempCanvas.width = croppedWidth * scale;
    tempCanvas.height = croppedHeight * scale;
    const tempCtx = tempCanvas.getContext("2d");

    // Pastikan canvas sementara tetap transparan
    // Tidak ada warna latar belakang, tetap transparan

    // Skala context agar gambar lebih tajam
    tempCtx.scale(scale, scale);

    // Pindahkan gambar dengan padding
    tempCtx.drawImage(
        canvas,
        minX - padding, minY - padding, croppedWidth, croppedHeight, // Area sumber (dengan padding)
        0, 0, croppedWidth, croppedHeight                            // Area tujuan
    );

    // Simpan gambar sebagai Base64
    const image = tempCanvas.toDataURL("image/png");
    document.querySelector(".image-tag").value = image;

    // Tampilkan gambar di halaman
    document.getElementById("results").innerHTML = 
        '<img src="' + image + '" alt="Signature" style="display: block; margin: 0 auto; max-width: 100%; height: auto;"/>';
}

// Event listener untuk mouse
canvas.addEventListener("mousedown", startDrawing);
canvas.addEventListener("mousemove", draw);
canvas.addEventListener("mouseup", stopDrawing);
canvas.addEventListener("mouseleave", stopDrawing);

// Event listener untuk touch
canvas.addEventListener("touchstart", startDrawing);
canvas.addEventListener("touchmove", draw);
canvas.addEventListener("touchend", stopDrawing);

// Tombol untuk menghapus
document.getElementById("clearBtn").addEventListener("click", clearCanvas);

// Tombol untuk menyimpan
document.getElementById("saveBtn").addEventListener("click", saveCanvas);

// Sesuaikan ukuran canvas saat memuat dan saat mengubah ukuran layar
window.addEventListener("load", resizeCanvas);
window.addEventListener("resize", resizeCanvas);


    </script>
</body>
</html>


