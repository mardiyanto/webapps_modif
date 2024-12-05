<?php
    if(strpos($_SERVER['REQUEST_URI'],"pages")){
        exit(header("Location:../index.php"));
    }
    
    $namars        = getOne("select setting.nama_instansi from setting");
    $nopersetujuan = "";
    $norawat       = "";
    
    $_sql          = "select * from antripersetujuanrawatinap" ;  
    $hasil         = bukaquery2($_sql);
    while ($data = mysqli_fetch_array ($hasil)){
        $nopersetujuan = $data['no_persetujuan'];
        $norawat      = $data['no_rawat'];
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
    
    $tanggal                        = "";        
    $nama_pj                        = "";        
    $no_ktppj                       = "";        
    $pendidikan_pj                  = "";        
    $alamatpj                       = "";        
    $no_telppj                      = "";        
    $ruang                          = "";        
    $kelas                          = "";        
    $hubungan                       = "";        
    $hak_kelas                      = "";        
    $nama_alamat_keluarga_terdekat  = "";        
    $bayar_secara                   = "";  
    $_sql2  = "select DATE_FORMAT(surat_persetujuan_rawat_inap.tanggal,'%d-%m-%Y') as tanggal,surat_persetujuan_rawat_inap.nama_pj,
               surat_persetujuan_rawat_inap.no_ktppj,surat_persetujuan_rawat_inap.pendidikan_pj,surat_persetujuan_rawat_inap.alamatpj,
               surat_persetujuan_rawat_inap.no_telppj,surat_persetujuan_rawat_inap.ruang,surat_persetujuan_rawat_inap.kelas,
               surat_persetujuan_rawat_inap.hubungan,surat_persetujuan_rawat_inap.hak_kelas,surat_persetujuan_rawat_inap.nama_alamat_keluarga_terdekat,
               surat_persetujuan_rawat_inap.bayar_secara from surat_persetujuan_rawat_inap where surat_persetujuan_rawat_inap.no_surat='$nopersetujuan'" ;  
    $hasil2 = bukaquery2($_sql2);
    while ($data2  = mysqli_fetch_array ($hasil2)){
        $tanggal                        = $data2['tanggal'];       
        $nama_pj                        = $data2['nama_pj'];        
        $no_ktppj                       = $data2['no_ktppj'];        
        $pendidikan_pj                  = $data2['pendidikan_pj'];        
        $alamatpj                       = $data2['alamatpj'];        
        $no_telppj                      = $data2['no_telppj'];        
        $ruang                          = $data2['ruang'];        
        $kelas                          = $data2['kelas'];        
        $hubungan                       = $data2['hubungan'];        
        $hak_kelas                      = $data2['hak_kelas'];        
        $nama_alamat_keluarga_terdekat  = $data2['nama_alamat_keluarga_terdekat'];        
        $bayar_secara                   = $data2['bayar_secara'];  
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIMKES Khanza</title>
    <!-- <script src="js/jquery.min.js"></script>
    <script src="js/webcam.min.js"></script> -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/modal.css" />
    <link rel="stylesheet" href="../css/csssurat.css" />
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
    <div class="header">
        <!-- Logo -->
        <img src="../logo.png" alt="Logo Lampung" class="logo">
        <!-- Nomor RM -->
        <div class="rm-number">NO. <?=$nopersetujuan;?></div>
        <!-- Teks Utama -->
        <div >
            <h2><?=$namars?></h2>
            <p>Jl. Pramuka No. 88 Rajabasa Bandar Lampung</p>
            <p>Telp. (0721) 706402 Fax. (0721) 706402</p>
        </div>
    </div>
    <!-- Garis Horizontal -->
    <div class="divider"></div>
        <h5 class="text-dark"><center><button class="btn btn-secondary" onclick="window.location.reload();">Refresh</button><br/><br/>PERSETUJUAN PASIEN RAWAT INAP </center></h5>
        <h7 class="text-dark"><center>Tanggal <?=$tanggal;?></center></h7><br/>
        <form method="POST" action="pages/storeImage.php" onsubmit="return validasiIsi();" enctype=multipart/form-data>
            <input type="hidden" name="nopersetujuan" value="<?=$nopersetujuan;?>">
            <input type="hidden" name="image" class="image-tag">
            
            <table class="default" width="100%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="25%">Nama Pasien</td>
                    <td width="75%">: <?=$nm_pasien;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Nomor Rekam Medis</td>
                    <td width="75%">: <?=$no_rkm_medis;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Jenis Kelamin</td>
                    <td width="75%">: <?=$jk;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Tanggal Lahir</td>
                    <td width="75%">: <?=$tgl_lahir;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Umur</td>
                    <td width="75%">: <?=$umur;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">No.Telp/No.HP</td>
                    <td width="75%">: <?=$no_tlp;?></td>
                </tr>
            </table>
            <br/>
            <h7 class="text-dark">
                Saya yang membuat persetujuan di bawah ini :
            </h7>
            <table class="default" width="97%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="25%">Nama</td>
                    <td width="75%">: <?=$nama_pj;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Alamat</td>
                    <td width="75%">: <?=$alamatpj;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">No.KTP/SIM</td>
                    <td width="75%">: <?=$no_ktppj;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Pendidikan</td>
                    <td width="75%">: <?=$pendidikan_pj;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">No.Telp/HP</td>
                    <td width="75%">: <?=$no_telppj;?></td>
                </tr>
            </table>
            <br/>
            <h7 class="text-dark">
                Dengan ini menyatakan dengan sesungguhnya bahwa saya setuju untuk dilakukan Rawat Inap di <?=$namars;?> di ruang : <?=$ruang;?> Kelas : <?=$kelas;?> Terhadap <?=$hubungan;?>
                <br/>
                Hak kelas perawatan : <?=$hak_kelas;?> 
                <br/>
                Nama dan alamat keluarga terdekat : <?=$nama_alamat_keluarga_terdekat;?> 
                <br/>
                Demi kelancaran pelayanan perawatan, pengobatan dan administrasi, dengan ini juga menyatakan :
            </h7>
            <table class="default" width="97%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="5%" valign='top'>a.</td>
                    <td width="95%" valign='top' align='justify'>Setuju dan memberi ijin kepada dokter yang bersangkutan untuk merawat saya/pasien tersebut diatas</td>
                </tr>
                <tr class="text-dark">
                    <td width="5%" valign='top'>b.</td>
                    <td width="95%" valign='top' align='justify'>Dengan ini menyatakan dengan sesungguhnya bahwa seluruh pembiayaan pelayanan di <?=$namars;?> akan saya bayarkan secara <?=$bayar_secara;?>, dan bersedia untuk melengkapi berkas kelengkapannya. Apabila dalam waktu 3 x 24 Jam tidak dapat menunjukkan kartu/kelengkapan lainnya, maka saya siap untuk membayarkan semua pelayanan dan tindakan di <?=$namars;?>.</td>
                </tr>
                <tr class="text-dark">
                    <td width="5%" valign='top'>c.</td>
                    <td width="95%" valign='top' align='justify'>Telah menyetujui dan bersedia mentaati segala peraturan yang berlaku di <?=$namars;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="5%" valign='top'>d.</td>
                    <td width="95%" valign='top' align='justify'>Memberi kuasa kepada Dokter untuk memberikan keterangan yang diperlukan oleh pihak penanggung biaya perawatan saya / pasien tersebut diatas</td>
                </tr>
            </table>
            <br/>
            <br/>
            <h7 class="text-dark"><b>TANDATANGAN DI SINI</b></h7>
            <canvas id="signaturePad" class="signature-pad"></canvas><br>
            <button type="button" id="clearBtn" class="btn btn-secondary">Ulangi Tanda Tangan</button><br><br>
            <h7 class="text-dark"><center>Yang Membuat Persetujuan</center></h7>
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
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        // Inisialisasi canvas
        const canvas = document.getElementById("signaturePad");
        const ctx = canvas.getContext("2d");
        let isDrawing = false;

        // Variabel untuk menyimpan status gambar
        let savedImage = null;

        // Resize canvas agar sesuai dengan layar
        function resizeCanvas() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = 300; // Tinggi tetap

            // Gambar ulang jika ada gambar yang disimpan
            if (savedImage) {
                const img = new Image();
                img.src = savedImage;
                img.onload = () => {
                    ctx.drawImage(img, 0, 0);
                };
            }
        }

        // Mendapatkan posisi relatif pada canvas
        function getPos(event) {
            const rect = canvas.getBoundingClientRect();
            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        // Mulai menggambar
        function startDrawing(event) {
            isDrawing = true;
            const pos = getPos(event);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        }

        // Menggambar
        function draw(event) {
            if (!isDrawing) return;

            const pos = getPos(event);
            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.strokeStyle = "#000";
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();

            // Simpan status gambar setiap kali menggambar
            savedImage = canvas.toDataURL();
        }

        // Berhenti menggambar
        function stopDrawing() {
            isDrawing = false;
            ctx.closePath();

            // Simpan status gambar terakhir
            savedImage = canvas.toDataURL();
        }

        // Menghapus canvas
        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            savedImage = null; // Reset gambar
        }

        // Fungsi untuk crop tanda tangan
        function cropSignature() {
            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let xMin = canvas.width, yMin = canvas.height, xMax = 0, yMax = 0;

            // Loop untuk menemukan batas tanda tangan
            for (let y = 0; y < imgData.height; y++) {
                for (let x = 0; x < imgData.width; x++) {
                    const index = (y * imgData.width + x) * 4;
                    const alpha = imgData.data[index + 3];
                    if (alpha > 0) { // Pixel aktif
                        if (x < xMin) xMin = x;
                        if (y < yMin) yMin = y;
                        if (x > xMax) xMax = x;
                        if (y > yMax) yMax = y;
                    }
                }
            }

            // Crop data gambar
            const croppedWidth = xMax - xMin;
            const croppedHeight = yMax - yMin;

            const croppedCanvas = document.createElement("canvas");
            croppedCanvas.width = croppedWidth;
            croppedCanvas.height = croppedHeight;
            const croppedCtx = croppedCanvas.getContext("2d");

            croppedCtx.putImageData(ctx.getImageData(xMin, yMin, croppedWidth, croppedHeight), 0, 0);
            return croppedCanvas.toDataURL();
        }

        // Menyimpan gambar tanda tangan
        function saveCanvas() {
            if (!savedImage) {
                alert("Tanda tangan kosong. Silakan tanda tangan terlebih dahulu.");
                return;
            }

            const croppedImage = cropSignature(); // Crop gambar
            document.querySelector(".image-tag").value = croppedImage;
            document.getElementById("results").innerHTML = `<img src="${croppedImage}" alt="Signature" style="max-width: 100%;"/>`;
        }

        // Event listeners
        canvas.addEventListener("mousedown", startDrawing);
        canvas.addEventListener("mousemove", draw);
        canvas.addEventListener("mouseup", stopDrawing);
        canvas.addEventListener("mouseleave", stopDrawing);

        canvas.addEventListener("touchstart", (event) => {
            event.preventDefault();
            startDrawing(event);
        });
        canvas.addEventListener("touchmove", (event) => {
            event.preventDefault();
            draw(event);
        });
        canvas.addEventListener("touchend", (event) => {
            event.preventDefault();
            stopDrawing();
        });

        document.getElementById("clearBtn").addEventListener("click", clearCanvas);
        document.getElementById("saveBtn").addEventListener("click", saveCanvas);

        window.addEventListener("resize", resizeCanvas);
        window.addEventListener("load", resizeCanvas);
    </script>
</body>
</html>

