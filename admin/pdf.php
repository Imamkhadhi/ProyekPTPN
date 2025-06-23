<?php
require "vendor/autoload.php";
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'landscape'); // Menggunakan ukuran A4 lanskap untuk lebih banyak ruang

$html = '
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 7px; margin: 5mm; } /* Mengurangi ukuran font dan margin */
    table { border-collapse: collapse; width: 100%; table-layout: fixed; }
    td, th { border: 1px solid black; padding: 1px; text-align: center; vertical-align: middle; } /* Mengurangi padding */
    .no-border { border: none !important; }
    .header-title { text-align: center; font-weight: bold; font-size: 11px; } /* Mengurangi ukuran font header */
    .sub-header { text-align: center; font-size: 9px; margin: 3px 0; } /* Mengurangi ukuran font sub-header dan margin */
    .signature-table td { text-align: center; padding-top: 10px; border: none !important; } /* Mengurangi padding atas */
    .footer-table td { font-size: 8px; border: 1px solid black; text-align: center; padding: 2px; } /* Mengurangi ukuran font dan padding */
    .small-text { font-size: 6px; } /* Gaya baru untuk teks yang lebih kecil jika diperlukan */

    /* Menyesuaikan lebar kolom agar tidak pecah */
    /* Total 24 kolom di tabel utama (1 Jam + 2 Uap Masuk + 2 Uap Bekas + 1 Oli + 4 Bantalan + 2 Cooling + 6 Panel + 2 BPV + 1 %Sesuai + 1 Keterangan + 1 Rencana + 1 Paraf) */
    /* Total %: 100% */
    .col-jam { width: 4%; }
    .col-uap-p1-tek { width: 3%; }
    .col-uap-p1-temp { width: 3%; }
    .col-uap-p2-tek { width: 3%; }
    .col-uap-p2-temp { width: 3%; }
    .col-oli { width: 4%; }
    .col-bantalan-lub { width: 2.5%; }
    .col-bantalan-cent { width: 2.5%; }
    .col-bantalan-impc { width: 2.5%; }
    .col-bantalan-ii { width: 2.5%; }
    .col-bantalan-iii { width: 2.5%; }
    .col-bantalan-iv { width: 2.5%; }
    .col-cooling-masuk { width: 3%; }
    .col-cooling-keluar { width: 3%; }
    .col-panel-volt { width: 3%; }
    .col-panel-ampere { width: 3%; }
    .col-panel-kw { width: 3%; }
    .col-panel-cosp { width: 3%; }
    .col-panel-frek { width: 3%; }
    .col-bpv-tek { width: 3%; }
    .col-bpv-persen { width: 3%; } /* %Sesuai */
    .col-keterangan { width: 8%; } /* Sesuaikan lebar */
    .col-rencana { width: 8%; } /* Sesuaikan lebar */
    .col-paraf { width: 5%; } /* Sesuaikan lebar */

    /* Gaya untuk catatan dan tanda tangan agar sejajar seperti Gambar 1 */
    .bottom-section {
        width: 100%;
        margin-top: 5px;
        display: table; /* Gunakan display:table untuk struktur tabel nyata */
        table-layout: fixed;
    }
    .bottom-row {
        display: table-row;
    }
    .bottom-cell {
        display: table-cell;
        vertical-align: top; /* Penjajaran atas */
        padding: 0 2px; /* Sedikit padding di antara sel */
    }
    .notes-box {
        height: 30px; /* Tinggi kotak catatan */
        border: 1px solid black;
        width: 100%;
        margin-top: 2px;
    }
    .signature-container {
        text-align: center;
    }
    .signature-lines {
        margin-top: 20px; /* Jarak untuk baris tanda tangan */
    }
    .signature-line-text {
        font-size: 8px;
    }
</style>

<table class="no-border" style="margin-bottom: 3px;">
    <tr>
        <td class="no-border" width="10%" style="text-align: left;">
            <img src="logo.png" width="60" height="30">
        </td>
        <td class="no-border" width="30%" style="text-align: left;">
            <b>PTPN:</b> PTPN II<br>
            <b>PKS:</b> PKS PAGAR MERBAU
        </td>
        <td class="no-border header-title" width="40%">
            LAPORAN KERJA STASIUN TURBIN UAP NO: ______
        </td>
        <td class="no-border" width="20%" style="font-size: 8px; text-align: right;">
            No. Form: ______ <br>
            Tgl Eff.: _______ <br>
            Halaman: ___ dari ___
        </td>
    </tr>
</table>

<div class="sub-header">Shift ________ &nbsp;&nbsp;&nbsp;&nbsp; Hari ________ &nbsp;&nbsp;&nbsp;&nbsp; Tanggal ____________</div>

<table>
    <colgroup>
        <col class="col-jam">
        <col class="col-uap-p1-tek">
        <col class="col-uap-p1-temp">
        <col class="col-uap-p2-tek">
        <col class="col-uap-p2-temp">
        <col class="col-oli">
        <col class="col-bantalan-lub">
        <col class="col-bantalan-cent">
        <col class="col-bantalan-impc">
        <col class="col-bantalan-ii">
        <col class="col-bantalan-iii">
        <col class="col-bantalan-iv">
        <col class="col-cooling-masuk">
        <col class="col-cooling-keluar">
        <col class="col-panel-volt">
        <col class="col-panel-ampere">
        <col class="col-panel-kw">
        <col class="col-panel-cosp">
        <col class="col-panel-frek">
        <col class="col-bpv-tek">
        <col class="col-bpv-persen">
        <col class="col-keterangan">
        <col class="col-rencana">
        <col class="col-paraf">
    </colgroup>
    <thead>
        <tr>
            <td rowspan="3">Jam</td>
            <td colspan="2">Uap Masuk (P1)</td>
            <td colspan="2">Uap Bekas (P2)</td>
            <td rowspan="3">Tekanan Oli<br>(kg/cm²)</td>
            <td colspan="4">Temperatur Bantalan (°C)</td>
            <td colspan="2">Temperatur Cooling Water (°C)</td>
            <td colspan="6">Penunjukan di Panel Turbin</td>
            <td colspan="2">BPV</td>
            <td rowspan="3">%Sesuai</td> <td rowspan="3">Keterangan Kendala</td>
            <td rowspan="3">Rencana Tindak Lanjut</td>
            <td rowspan="3">Paraf Asisten</td>
        </tr>
        <tr>
            <td rowspan="2">Tekanan<br>(kg/cm²)</td>
            <td rowspan="2">Temp<br>(°C)</td>
            <td rowspan="2">Tekanan<br>(kg/cm²)</td>
            <td rowspan="2">Temp<br>(°C)</td>
            <td rowspan="2">Lub</td>
            <td rowspan="2">Cent</td>
            <td rowspan="2">Impc</td>
            <td rowspan="2">II</td>
            <td rowspan="2">III</td>
            <td rowspan="2">IV</td>
            <td rowspan="2">Masuk</td>
            <td rowspan="2">Keluar</td>
            <td rowspan="2">Voltase<br>(V)</td>
            <td rowspan="2">Ampere<br>(A)</td>
            <td rowspan="2">KW</td>
            <td rowspan="2">Cos φ</td>
            <td rowspan="2">Frekuensi<br>(Hz)</td>
            <td rowspan="2">Tekanan<br>(kg/cm²)</td>
        </tr>
        <tr></tr>
    </thead>
    <tbody>';

$jam = [
    "NORMA<br>19:30", "20:00", "20:30", "21:00", "21:30", "22:00", "22:30", "23:00", "23:30",
    "00:00", "00:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00",
    "04:30", "05:00", "05:30", "06:00", "06:30", "07:00", "Jlh Shift I"
];

// Baris data dari gambar (baris "NORMA")
$data_norma = [
    19, // Tekanan Uap Masuk
    280, // Temp Uap Masuk
    3.2, // Tekanan Uap Bekas
    140, // Temp Uap Bekas
    3.9, // Tekanan Oli
    65, // Lub
    65, // Cent
    '', '', '', '', // Impc, II, III, IV - kosong di contoh
    380, // Masuk Cooling Water
    '', // Keluar Cooling Water - kosong di contoh
    950, // Voltase
    550, // Ampere
    0.8, // KW
    50, // Cos p
    3.2, // Frekuensi
    '', // %Sesuai
];

foreach ($jam as $index => $j) {
    $html .= '<tr>';
    $html .= '<td>' . $j . '</td>';
    if ($index === 0) { // Isi data NORMA untuk baris pertama
        $html .= '<td>' . $data_norma[0] . '</td>'; // Tekanan Uap Masuk
        $html .= '<td>' . $data_norma[1] . '</td>'; // Temp Uap Masuk
        $html .= '<td>' . $data_norma[2] . '</td>'; // Tekanan Uap Bekas
        $html .= '<td>' . $data_norma[3] . '</td>'; // Temp Uap Bekas
        $html .= '<td>' . $data_norma[4] . '</td>'; // Tekanan Oli
        $html .= '<td>' . $data_norma[5] . '</td>'; // Lub
        $html .= '<td>' . $data_norma[6] . '</td>'; // Cent
        $html .= '<td></td>'; // Impc - kosong
        $html .= '<td></td>'; // II - kosong
        $html .= '<td></td>'; // III - kosong
        $html .= '<td></td>'; // IV - kosong
        $html .= '<td>' . $data_norma[7] . '</td>'; // Masuk Cooling Water
        $html .= '<td></td>'; // Keluar Cooling Water - kosong
        $html .= '<td>' . $data_norma[8] . '</td>'; // Voltase
        $html .= '<td>' . $data_norma[9] . '</td>'; // Ampere
        $html .= '<td>' . $data_norma[10] . '</td>'; // KW
        $html .= '<td>' . $data_norma[11] . '</td>'; // Cos p
        $html .= '<td>' . $data_norma[12] . '</td>'; // Frekuensi
        $html .= '<td>' . $data_norma[13] . '</td>'; // Tekanan BPV
        $html .= '<td></td>'; // %Sesuai - kosong
        $html .= '<td rowspan="' . (count($jam) - $index) . '"></td>'; // Keterangan Kendala - Span ke bawah
        $html .= '<td rowspan="' . (count($jam) - $index) . '"></td>'; // Rencana Tindak Lanjut - Span ke bawah
        $html .= '<td rowspan="' . (count($jam) - $index) . '"></td>'; // Paraf Asisten - Span ke bawah
    } else {
        for ($k = 0; $k < 21; $k++) { // Kolom data yang tersisa sebelum kolom yang di-span (total 24 - 3 kolom rowspan)
            $html .= '<td></td>';
        }
    }
    $html .= '</tr>';
}

$html .= '</tbody></table><br>';

$html .= '
<div class="bottom-section">
    <div class="bottom-cell" style="width: 40%;">
        <table class="no-border" width="100%">
            <tr><td class="no-border" style="text-align: left;">Catatan Asisten Teknik Pabrik:</td></tr>
            <tr><td class="notes-box"></td></tr>
        </table>
    </div>
    <div class="bottom-cell" style="width: 30%;">
        <table class="signature-table" width="100%">
            <tr>
                <td colspan="2">Digunakan dlm Standup mesin</td>
            </tr>
            <tr>
                <td colspan="2" class="signature-lines">
                    (_______________________)<br>
                    <span class="signature-line-text">Asisten Pengolahan</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="bottom-cell" style="width: 30%;">
        <table class="signature-table" width="100%">
            <tr>
                <td colspan="2">Diketahui Oleh</td>
            </tr>
            <tr>
                <td colspan="2" class="signature-lines">
                    (_______________________)<br>
                    <span class="signature-line-text">Manager</span>
                </td>
            </tr>
        </table>
    </div>
</div>

<table width="30%" class="footer-table" style="margin-top: 5px; float: left;">
    <tr><td><b>Uraian</b></td><td><b>H.I</b></td><td><b>S.d H.I</b></td></tr>
    <tr><td>Jam Beroperasi</td><td></td><td></td></tr>
</table>';

$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("laporan_turbin_gambar1.pdf", ["Attachment" => false]);
?>