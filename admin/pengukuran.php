<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Monitoring Turbin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 80px; /* Jarak dari navbar tetap */
        }

        /* Navbar styling */
        .custom-navbar {
            background: linear-gradient(to bottom, rgb(249, 249, 249), rgb(248, 251, 248));
            padding: 5px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }

        .navbar-brand img {
            height: 50px;
            width: 100px;
        }

        .logout-btn {
            color: black;
            font-size: 30px;
            text-decoration: none;
            margin-right: 0;
        }

        .logout-btn:hover {
            color: orange;
        }

        .navbar-nav .nav-link {
            color: black;
            font-weight: bold;
            margin-right: 20px;
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            border-bottom: 2px solid orange;
        }

        .custom-navbar .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .navbar-collapse {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-nav {
            flex-grow: 1;
            justify-content: center;
            display: flex;
        }

        @media (max-width: 768px) {
            .custom-navbar .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .navbar-brand {
                margin-right: 0;
                padding-left: 0;
            }

            .navbar-brand img {
                height: 40px;
                width: auto;
            }

            .navbar-toggler {
                margin-left: auto;
            }

            .navbar-collapse {
                display: block;
                justify-content: start;
                align-items: start;
            }

            .navbar-nav {
                background-color: rgb(253, 253, 253);
                border-radius: 5px;
                padding: 5px;
                text-align: left;
                margin-left: 0 !important;
                margin-top: 10px;
                width: 100%;
                display: block;
                justify-content: start;
                flex-grow: unset;
            }

            .navbar-nav .nav-item {
                text-align: left;
                width: 100%;
            }

            .navbar-nav .nav-link {
                display: block;
                width: 100%;
                padding-left: 10px;
                margin-right: 0;
            }

            .collapse.navbar-collapse .ms-auto {
                display: block;
                width: 100%;
                text-align: left;
                padding: 10px;
                margin-top: 10px;
                margin-left: 0 !important;
            }
            .collapse.navbar-collapse .ms-auto .logout-btn {
                font-size: 20px;
                text-align: left;
                display: block;
                width: 100%;
            }
        }
        /* End Navbar styling */

        /* Gauge and description styling */
        h3.section-title {
            text-align: center;
            margin: 50px 0 20px;
            font-weight: bold;
            color: #333;
        }

        .gauge-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            justify-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 10px;
            box-sizing: border-box;
            transition: all 0.2s ease-in-out;
        }

        .gauge-frame {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 100%;
            box-sizing: border-box;
            transition: all 0.2s ease-in-out;
        }

        .gauge-frame:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        canvas {
            border-radius: 50%;
            margin-right: 20px;
            /* Perbesar ukuran canvas di sini */
            width: 200px; /* Lebar baru */
            height: 200px; /* Tinggi baru */
        }

        .gauge-description {
            text-align: left;
            flex-grow: 1;
            font-size: 0.85em;
            line-height: 1.3;
        }

        .gauge-description h4 {
            margin-top: 0;
            margin-bottom: 8px;
            color: #333;
            font-size: 1.1em;
        }
        .gauge-description p {
            margin: 0;
            padding: 2px 0;
            display: flex;
            align-items: center;
        }
        .gauge-description p span {
            display: inline-flex;
            width: 1.5em;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        /* Responsif untuk gauge-container */
        @media (max-width: 992px) {
            .gauge-container {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 15px;
                padding: 30px 10px;
            }
            .gauge-frame {
                padding: 10px;
            }
            canvas {
                width: 150px; /* Sesuaikan ukuran canvas untuk tablet */
                height: 150px;
                margin-right: 15px;
            }
        }

        @media (max-width: 576px) {
            .gauge-container {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 20px 10px;
            }
            .gauge-frame {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 15px;
            }
            canvas {
                width: 200px; /* Ukuran canvas mobile */
                height: 200px;
                margin-right: 0;
                margin-bottom: 10px;
            }
            .gauge-description {
                text-align: center;
            }
            .gauge-description p {
                justify-content: center;
            }
        }

        /* Tabel yang tidak lagi dibutuhkan (bisa dihapus sepenuhnya dari HTML jika diinginkan) */
        table {
            display: none;
        }
    </style>
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <?php
    include "koneksi.php";
    define('HOST', "http://localhost/assesment2/index.html");
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../images/logo_perkebunan_nusantara.png" alt="Logo PTPN" />
            </a>

            <button class="navbar-toggler text-white ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'pengukuran.php' ? 'active' : '' ?>" href="pengukuran.php">Monitoring</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'pengisian.php' ? 'active' : '' ?>" href="pengisian.php">Pengisian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'jurnal.php' ? 'active' : '' ?>" href="jurnal.php">Jurnal</a>
                    </li>
                </div>

                <div class="ms-auto d-none d-lg-block">
                    <a class="logout-btn" href="login.php" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>

                <div class="ms-auto d-lg-none">
                    <a class="logout-btn" href="login.php" title="Logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <h3 class="section-title">Meteran Turbin</h3>
    <div class="gauge-container">
        <div>
            <div class="gauge-frame">
                <canvas id="gauge1" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Uap Masuk</h4>
                    <p><span>🟥</span> : 0 - 14,9 Bar</p>
                    <p><span>🟨</span> : 15 - 17,9 Bar</p>
                    <p><span>🟩</span> : 18 - 20 Bar</p>
                    <p><span>🟥</span> : 20,1 - 22 Bar</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge2" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Uap Sisa</h4>
                    <p><span>🟥</span> : 0 - 1,9 Bar</p>
                    <p><span>🟨</span> : 2 - 2,7 Bar</p>
                    <p><span>🟩</span> : 2,8 - 3 Bar</p>
                    <p><span>🟥</span> : 3,1 - 3,2 Bar</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge3" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Tekanan Oli</h4>
                    <p><span>🟥</span> : < 1 Bar</p>
                    <p><span>🟩</span> : 1 - 1,5 Bar</p>
                    <p><span>🟥</span> : > 1,5 Bar</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge4" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Cooling Water</h4>
                    <p><span>🟩</span> : 0 - 63 °C</p>
                    <p><span>🟥</span> : 64 - seterusnya</p>
                </div>
            </div>
        </div>
    </div>
    <h3 class="section-title">Meteran Panel</h3>
    <div class="gauge-container">
        <div>
            <div class="gauge-frame">
                <canvas id="gauge5" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Voltase</h4>
                    <p><span>🟨</span> : < 370 V</p>
                    <p><span>🟩</span> : 371 - 390 V</p>
                    <p><span>🟥</span> : > 390 V</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge6" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Amper</h4>
                    <p><span>🟩</span> : < 600 A</p>
                    <p><span>🟥</span> : > 600 A</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge7" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Daya (KW)</h4>
                    <p><span>🟩</span> : < 550 kW</p>
                    <p><span>🟨</span> : 551 - 599 kW</p>
                    <p><span>🟥</span> : > 600 kW</p>
                </div>
            </div>
        </div>
        <div>
            <div class="gauge-frame">
                <canvas id="gauge8" width="200" height="200"></canvas>
                <div class="gauge-description">
                    <h4>Frekuensi (Hz)</h4>
                    <p><span>🟥</span> : < 49 Hz</p>
                    <p><span>🟩</span> : 49 - 51 Hz</p>
                    <p><span>🟥</span> : > 51 Hz</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menggambar meteran
        function drawGauge(canvasId, actualValue, actualMaxParamValue, rangesData, customLabels = null) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');
            const radius = canvas.width / 2;
            const center = radius;
            const gaugeScaleMax = 6; // Tetap gunakan 6 skala untuk internal gauge mapping

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const mapToGaugeScale = (val) => (val / actualMaxParamValue) * gaugeScaleMax;

            const mapEmojiToColor = (emoji) => {
                if (emoji === '🟥') return 'red';
                if (emoji === '🟨') return 'gold';
                if (emoji === '🟩') return 'green';
                return '#ccc';
            };

            // Background gauge
            ctx.beginPath();
            ctx.arc(center, center, radius - 10, Math.PI, 0, true); // true = counter-clockwise (bagian atas lingkaran)
            ctx.lineWidth = 4;
            ctx.strokeStyle = '#ccc';
            ctx.stroke();

            // Draw colored ranges
            for (const range of rangesData) {
                let startValActual = 0;
                let endValActual = actualMaxParamValue;

                if (range.start_op === '<') {
                    endValActual = range.end;
                } else if (range.end_op === '>') {
                    startValActual = range.start;
                } else {
                    startValActual = range.start;
                    endValActual = range.end;
                }

                let startValNormalized = mapToGaugeScale(startValActual);
                let endValNormalized = mapToGaugeScale(endValActual);

                // Pastikan nilai berada dalam batas 0 dan gaugeScaleMax
                startValNormalized = Math.max(0, Math.min(gaugeScaleMax, startValNormalized));
                endValNormalized = Math.max(0, Math.min(gaugeScaleMax, endValNormalized));

                const startAngle = Math.PI - (endValNormalized / gaugeScaleMax) * Math.PI; // Ini akan menjadi sudut "kiri" range
                const endAngle = Math.PI - (startValNormalized / gaugeScaleMax) * Math.PI; // Ini akan menjadi sudut "kanan" range

                ctx.beginPath();
                ctx.arc(center, center, radius - 10, startAngle, endAngle, true); // true = counter-clockwise
                ctx.lineWidth = 4;
                ctx.strokeStyle = mapEmojiToColor(range.emoji);
                ctx.stroke();
            }

            // Major ticks and labels
            const labelsToDraw = customLabels || Array.from({length: gaugeScaleMax + 1}, (_, i) => {
                let calculatedLabel = (actualMaxParamValue * i) / gaugeScaleMax;
                return actualMaxParamValue < 10 ? parseFloat(calculatedLabel.toFixed(1)) : Math.round(calculatedLabel);
            });

            const numSegments = labelsToDraw.length - 1;

            for (let i = 0; i <= numSegments; i++) {
                const angle = Math.PI - (i / numSegments) * Math.PI; // Inversi perhitungan sudut

                const tickLength = 10;
                const x1 = center + Math.cos(angle) * (radius - 10 - tickLength);
                const y1 = center + Math.sin(angle) * (radius - 10 - tickLength);
                const x2 = center + Math.cos(angle) * (radius - 10);
                const y2 = center + Math.sin(angle) * (radius - 10);

                ctx.beginPath();
                ctx.moveTo(x1, y1);
                ctx.lineTo(x2, y2);
                ctx.lineWidth = 2;
                ctx.strokeStyle = 'black';
                ctx.stroke();

                const actualLabel = labelsToDraw[i];

                const labelOffset = radius - 25; // Sesuaikan nilai ini untuk mengontrol jarak label dari pusat
                const xLabel = center + Math.cos(angle) * labelOffset;
                const yLabel = center + Math.sin(angle) * labelOffset;

                ctx.font = '12px Arial';
                ctx.fillStyle = 'black';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(actualLabel, xLabel, yLabel);
            }

            // Minor ticks - SELALU 9 garis kecil di antara setiap garis besar
            const fixedMinorTickFactor = 9; // Tetapkan ini menjadi 9
            if (fixedMinorTickFactor > 0 && numSegments > 0) { // Pastikan ada segmen mayor untuk minor ticks
                for (let i = 0; i < numSegments; i++) {
                    // Iterasi untuk setiap garis kecil di dalam segmen
                    for (let j = 1; j <= fixedMinorTickFactor; j++) { // Mulai dari 1 untuk garis di antara, berakhir di fixedMinorTickFactor
                        const currentMinorTickValue = i + (j / (fixedMinorTickFactor + 1)); // Menghitung posisi minor tick secara proporsional
                        const angle = Math.PI - (currentMinorTickValue / numSegments) * Math.PI; // Inversi perhitungan sudut
                        
                        const tickLength = 5; // Panjang tick minor
                        const x1 = center + Math.cos(angle) * (radius - 10 - tickLength);
                        const y1 = center + Math.sin(angle) * (radius - 10 - tickLength);
                        const x2 = center + Math.cos(angle) * (radius - 10);
                        const y2 = center + Math.sin(angle) * (radius - 10);

                        ctx.beginPath();
                        ctx.moveTo(x1, y1);
                        ctx.lineTo(x2, y2);
                        ctx.lineWidth = 1;
                        ctx.strokeStyle = 'black';
                        ctx.stroke();
                    }
                }
            }


            // Draw needle
            const normalizedCurrentValue = Math.max(0, Math.min(actualMaxParamValue, actualValue));
            const needleAngle = Math.PI - (normalizedCurrentValue / actualMaxParamValue) * Math.PI; // Inversi perhitungan sudut

            const xNeedle = center + Math.cos(needleAngle) * (radius - 50);
            const yNeedle = center + Math.sin(needleAngle) * (radius - 50);

            ctx.beginPath();
            ctx.moveTo(center, center);
            ctx.lineTo(xNeedle, yNeedle);
            ctx.lineWidth = 4;
            ctx.strokeStyle = 'black';
            ctx.stroke();

            // Needle center dot
            ctx.beginPath();
            ctx.arc(center, center, 8, 0, 2 * Math.PI);
            ctx.fillStyle = 'black';
            ctx.fill();
        }

        // Data Rentang dan Panggilan drawGauge untuk setiap meteran
        // 1. Uap Masuk
        const uapMasukRanges = [
            { start: 0, end: 14.9, emoji: '🟥' },
            { start: 15, end: 17.9, emoji: '🟨' },
            { start: 18, end: 20, emoji: '🟩' },
            { start: 20.1, end: 22, emoji: '🟥' }
        ];
        const uapMasukLabels = [0, 10, 20, 30, 40]; // Label kustom
        // Nilai aktual dan maksimum disesuaikan kembali ke gambar 2, tapi label tetap custom
        drawGauge('gauge1', 22, 40, uapMasukRanges, uapMasukLabels);
        fetch('https://api-jurnal-ptpn.solvethink.id/api/uap-masuk')
            .then(response => response.json())
            .then(result => {
                const value = parseFloat(result.data.uap_masuk);
                const maxGaugeValue = 40;
                drawGauge('gauge1', value, maxGaugeValue, uapMasukRanges, uapMasukLabels);
            })
            .catch(error => {
                console.error('❌ Gagal mengambil data uap_masuk:', error);
            });

        // 2. Uap Sisa
        const uapSisaRanges = [
            { start: 0, end: 1.9, emoji: '🟥' },
            { start: 2, end: 2.7, emoji: '🟨' },
            { start: 2.8, end: 3, emoji: '🟩' },
            { start: 3.1, end: 3.2, emoji: '🟥' }
        ];
        const uapSisaLabels = [0, 1, 2, 3, 4, 5, 6]; // Label kustom
        drawGauge('gauge2', 3.2, 6, uapSisaRanges, uapSisaLabels);

        // 3. Tekanan Oli
        const tekananOliRanges = [
            { start_op: '<', end: 1, emoji: '🟥' },
            { start: 1, end: 1.5, emoji: '🟩' },
            { start_op: '>', start: 1.5, emoji: '🟥' }
        ];
        const tekananOliLabels = [0, 1, 2, 3, 4, 5, 6]; // Label kustom
        drawGauge('gauge3', 2, 6, tekananOliRanges, tekananOliLabels);

        // 4. Temperatur Cooling Water
        const coolingWaterRanges = [
            { start: 0, end: 63, emoji: '🟩' },
            { start: 64, end_op: '>', emoji: '🟥' }
        ];
        const coolingWaterLabels = [0, 20, 40, 60, 80, 100]; // Label kustom
        drawGauge('gauge4', 83, 100, coolingWaterRanges, coolingWaterLabels);

        // 5. Voltase
        const voltaseRanges = [
            { start_op: '<', end: 370, emoji: '🟨' },
            { start: 371, end: 390, emoji: '🟩' },
            { start_op: '>', start: 390, emoji: '🟥' }
        ];
        drawGauge('gauge5', 380, 400, voltaseRanges);

        // 6. Amper
        const amperRanges = [
            { start_op: '<', end: 600, emoji: '🟩' },
            { start_op: '>', start: 600, emoji: '🟥' }
        ];
        drawGauge('gauge6', 550, 700, amperRanges);

        // 7. KW
        const kwRanges = [
            { start_op: '<', end: 550, emoji: '🟩' },
            { start: 551, end: 599, emoji: '🟨' },
            { start_op: '>', start: 600, emoji: '🟥' }
        ];
        drawGauge('gauge7', 580, 700, kwRanges);

        // 8. Hz
        const hzRanges = [
            { start_op: '<', end: 49, emoji: '🟥' },
            { start: 49, end: 51, emoji: '🟩' },
            { start_op: '>', start: 51, emoji: '🟥' }
        ];
        drawGauge('gauge8', 50, 60, hzRanges);

    </script>

</body>
</html>