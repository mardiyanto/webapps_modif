<?php
    if(strpos($_SERVER['REQUEST_URI'],"pages")){
        exit(header("Location:../index.php"));
    }
    
    $nopernyataan  = "";
    $norawat       = "";
    $_sql          = "select * from antripersetujuan" ;  
    $hasil         = bukaquery2($_sql);
    while ($data = mysqli_fetch_array ($hasil)){
        $nopernyataan = $data['no_pernyataan'];
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
    
    $tanggal                = "";
    $diagnosa               = "";
    $tindakan               = "";
    $indikasi_tindakan      = "";
    $tata_cara              = "";
    $tujuan                 = "";
    $risiko                 = "";
    $komplikasi             = "";
    $prognosis              = "";
    $alternatif_dan_risikonya= "";
    $biaya                  = "";
    $lain_lain              = "";
    $kd_dokter              = "";
    $nip                    = "";
    $penerima_informasi     = "";
    $alasan_diwakilkan_penerima_informasi = "";
    $jk_penerima_informasi  = "";
    $tanggal_lahir_penerima_informasi = "";
    $umur_penerima_informasi= "";
    $alamat_penerima_informasi= "";
    $no_hp                  = "";
    $hubungan_penerima_informasi= "";
    $saksi_keluarga         = "";
    $_sql2  = "select persetujuan_penolakan_tindakan.tanggal,persetujuan_penolakan_tindakan.diagnosa,persetujuan_penolakan_tindakan.tindakan,persetujuan_penolakan_tindakan.indikasi_tindakan,
               persetujuan_penolakan_tindakan.tata_cara,persetujuan_penolakan_tindakan.tujuan,persetujuan_penolakan_tindakan.risiko,persetujuan_penolakan_tindakan.komplikasi,
               persetujuan_penolakan_tindakan.prognosis,persetujuan_penolakan_tindakan.alternatif_dan_risikonya,persetujuan_penolakan_tindakan.biaya,
               persetujuan_penolakan_tindakan.lain_lain,persetujuan_penolakan_tindakan.kd_dokter,persetujuan_penolakan_tindakan.nip,persetujuan_penolakan_tindakan.penerima_informasi,
               persetujuan_penolakan_tindakan.alasan_diwakilkan_penerima_informasi,if(persetujuan_penolakan_tindakan.jk_penerima_informasi='L','LAKI-LAKI','PEREMPUAN') as jk_penerima_informasi,
               DATE_FORMAT(persetujuan_penolakan_tindakan.tanggal_lahir_penerima_informasi,'%d-%m-%Y') as tanggal_lahir_penerima_informasi,
               persetujuan_penolakan_tindakan.umur_penerima_informasi,persetujuan_penolakan_tindakan.alamat_penerima_informasi,persetujuan_penolakan_tindakan.no_hp,
               persetujuan_penolakan_tindakan.hubungan_penerima_informasi,persetujuan_penolakan_tindakan.saksi_keluarga 
               from persetujuan_penolakan_tindakan where persetujuan_penolakan_tindakan.no_pernyataan='$nopernyataan'" ;  
    $hasil2 = bukaquery2($_sql2);
    while ($data2  = mysqli_fetch_array ($hasil2)){
        $tanggal                = $data2['tanggal'];
        $diagnosa               = $data2['diagnosa'];
        $tindakan               = $data2['tindakan'];
        $indikasi_tindakan      = $data2['indikasi_tindakan'];
        $tata_cara              = $data2['tata_cara'];
        $tujuan                 = $data2['tujuan'];
        $risiko                 = $data2['risiko'];
        $komplikasi             = $data2['komplikasi'];
        $prognosis              = $data2['prognosis'];
        $alternatif_dan_risikonya= $data2['alternatif_dan_risikonya'];
        $biaya                  = $data2['biaya'];
        $lain_lain              = $data2['lain_lain'];
        $kd_dokter              = $data2['kd_dokter'];
        $nip                    = $data2['nip'];
        $penerima_informasi     = $data2['penerima_informasi'];
        $alasan_diwakilkan_penerima_informasi = $data2['alasan_diwakilkan_penerima_informasi'];
        $jk_penerima_informasi  = $data2['jk_penerima_informasi'];
        $tanggal_lahir_penerima_informasi = $data2['tanggal_lahir_penerima_informasi'];
        $umur_penerima_informasi= $data2['umur_penerima_informasi'];
        $alamat_penerima_informasi= $data2['alamat_penerima_informasi'];
        $no_hp                  = $data2['no_hp'];
        $hubungan_penerima_informasi= $data2['hubungan_penerima_informasi'];
        $saksi_keluarga         = $data2['saksi_keluarga'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIMKES Khanza</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
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
        <div class="rm-number">Tanggal <?=$tanggal?></div>
        <!-- Teks Utama -->
        <div >
            <h2><?=$namars?></h2>
            <p>Jl. Pramuka No. 88 Rajabasa Bandar Lampung</p>
            <p>Telp. (0721) 706402 Fax. (0721) 706402</p>
        </div>
    </div>
    <!-- Garis Horizontal -->
    <div class="divider"></div>
        <h5 class="text-dark"><center><button class="btn btn-secondary" onclick="window.location.reload();">Refresh</button><br/><br/>Pernyataan Persetujuan/Penolakan Tindakan </center></h5>
        <h7 class="text-dark"><center>Nomor. <?=$nopernyataan;?></center></h7><br/>
        <form method="POST" action="pages/storeImage.php" onsubmit="return validasiIsi();" enctype=multipart/form-data>
            <input type="hidden" name="nopernyataan" value="<?=$nopernyataan;?>">
            <input type="hidden" name="image" class="image-tag">
            <h7 class="text-dark">
                Apabila pasien berusia dibawah 18 tahun atau tidak dapat memberikan persetujuan karena alasan lain (**) tidak dapat menandatangani surat pernyataan ini, 
                Pihak rumah sakit dapat mengambil kebijakan dengan memperoleh tanda tangan dari orang tua, pasangan, anggota keluarga terdekat atau wali pasien.<br/> 
                (**) Sebutkan alasan lainnya : <?=$alasan_diwakilkan_penerima_informasi?> <br/><br/>
                Yang bertanda tangan di bawah ini saya :
            </h7>
            <table class="default" width="100%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="30%">Nama</td>
                    <td width="70%">: <?=$penerima_informasi;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Jenis Kelamin</td>
                    <td width="75%">: <?=$jk_penerima_informasi;?> &nbsp;&nbsp;&nbsp;&nbsp;Tanggal Lahir : <?=$tanggal_lahir_penerima_informasi;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Alamat</td>
                    <td width="75%">: <?=$alamat_penerima_informasi;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Hubungan dengan pasien</td>
                    <td width="75%">: <?=$hubungan_penerima_informasi;?></td>
                </tr>
            </table>
            <br/>
            <h7 class="text-dark">
                Dengan ini menyatakan <select name="pilihansetuju" class="text-dark"><option value='Persetujuan'>Persetujuan</option><option value='Penolakan'>Penolakan</option></select> untuk dapat dilakukan tindakan kedokteran berupa : 
            </h7>
            <table class="default" width="100%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="30%">Tindakan Kedokteran</td>
                    <td width="60%">: <?=$tindakan;?></td>
                    <td width="10%"><input type="checkbox" name="tindakan_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Diagnosa</td>
                    <td width="60%">: <?=$diagnosa;?></td>
                    <td width="10%"><input type="checkbox" name="diagnosa_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Indikasi Tindakan</td>
                    <td width="60%">: <?=$indikasi_tindakan;?></td>
                    <td width="10%"><input type="checkbox" name="indikasi_tindakan_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Tata Cara</td>
                    <td width="60%">: <?=$tata_cara;?></td>
                    <td width="10%"><input type="checkbox" name="tata_cara_konfirmasi"></td>
                </tr> 
                <tr class="text-dark">
                    <td width="30%">Tujuan</td>
                    <td width="60%">: <?=$tujuan;?></td>
                    <td width="10%"><input type="checkbox" name="tujuan_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Risiko</td>
                    <td width="60%">: <?=$risiko;?></td>
                    <td width="10%"><input type="checkbox" name="risiko_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Komplikasi</td>
                    <td width="60%">: <?=$komplikasi;?></td>
                    <td width="10%"><input type="checkbox" name="komplikasi_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Prognosis</td>
                    <td width="60%">: <?=$prognosis;?></td>
                    <td width="10%"><input type="checkbox" name="prognosis_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Alternatif & Resikonya</td>
                    <td width="60%">: <?=$alternatif_dan_risikonya;?></td>
                    <td width="10%"><input type="checkbox" name="alternatif_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Lain-lain</td>
                    <td width="60%">: <?=$lain_lain;?></td>
                    <td width="10%"><input type="checkbox" name="lain_lain_konfirmasi"></td>
                </tr>
                <tr class="text-dark">
                    <td width="30%">Biaya</td>
                    <td width="60%">: <?=$biaya;?></td>
                    <td width="10%"><input type="checkbox" name="biaya_konfirmasi"></td>
                </tr>
            </table>
            <br/>
            <h7 class="text-dark">
                Terhadap Pasien : 
            </h7>
            <table class="default" width="100%" border="0" align="center" cellpadding="3px" cellspacing="0px">
                <tr class="text-dark">
                    <td width="30%">Nama Pasien</td>
                    <td width="70%">: <?=$nm_pasien;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Nomor Rekam Medis</td>
                    <td width="75%">: <?=$no_rkm_medis;?></td>
                </tr>
                <tr class="text-dark">
                    <td width="25%">Jenis Kelamin</td>
                    <td width="75%">: <?=$jk;?> &nbsp;&nbsp;&nbsp;&nbsp;Tanggal Lahir : <?=$tgl_lahir;?></td>
                </tr>
            </table>
            <br/>
            <h7 class="text-dark">
                Melalui penyataan ini segala resiko dan yang kemungkinan terjadi sebagai akibat dari pengambilan keputusan ini menjadi tanggung jawab saya pribadi dan keluarga
            </h7>
            <br/><br/>
            <h7 class="text-dark"><b>TANDATANGAN DI SINI</b></h7>
            <canvas id="signaturePad" class="signature-pad"></canvas><br>
            <button type="button" id="clearBtn" class="btn btn-secondary">Ulangi Tanda Tangan</button><br><br>
            <h7 class="text-dark"><center>Yang Membuat Pernyataan</center></h7>
            <div class="row">
               
                <div class="col-md-12">
                    <div id="results"><h7 class="text-success"><center>Gambar akan diambil jika anda sudah mengeklik ya</center></h7></div>
                    <span id="MsgIsi1" style="color:#CC0000; font-size:10px;"></span>
                </div>
                <div class="col-md-12 text-center">
                    <br>
                    <input type="button" class="btn btn-warning" value="Ya, Saya sebagai pembuat pernyataan" id="saveBtn">
                    <button class="btn btn-danger">Simpan</button>
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

