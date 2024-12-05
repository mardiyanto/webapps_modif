<?php
    if(strpos($_SERVER['REQUEST_URI'],"pages")){
        exit(header("Location:../index.php"));
    }
    
    $nopernyataan="";
    $cari    = trim(isset($_GET['iyem']))?trim($_GET['iyem']):NULL;
    $cari    = json_decode(encrypt_decrypt($cari,"d"),true);
    if (isset($cari["usere"])) {
        if(($cari["usere"]==USERHYBRIDWEB)&&($cari["passwordte"]==PASHYBRIDWEB)){
            $nopernyataan=validTeks4($cari['nopernyataan'],20); 
        }else{
            exit(header("Location:../index.php"));
        }
    }else{
        exit(header("Location:../index.php"));
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>SIMKES Khanza</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
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
        <h5 class="text-dark"><h5><center>Pernyataan Pulang Atas Permintaan Sendiri No. <?=$nopernyataan;?></center></h5><br/>
        <form method="POST" action="pages/storeImage2.php" onsubmit="return validasiIsi();" enctype=multipart/form-data>
            <input type="hidden" name="nopernyataan" value="<?=$nopernyataan;?>">
            <input type="hidden" name="image" class="image-tag">
            <h7 class="text-dark">
                <center>
                    Saksi 1 Keluarga<br/>
                    <?=getOne("select surat_pulang_atas_permintaan_sendiri.saksi_keluarga from surat_pulang_atas_permintaan_sendiri where surat_pulang_atas_permintaan_sendiri.no_surat='$nopernyataan'")?>
                </center>
            </h7>
            <h7 class="text-dark"><b>TANDATANGAN DI SINI</b></h7>
            <canvas id="signaturePad" class="signature-pad"></canvas><br>
            <button type="button" id="clearBtn" class="btn btn-secondary">Ulangi Tanda Tangan</button><br><br>
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

