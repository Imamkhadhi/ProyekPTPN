<?php
require "../vendor/autoload.php";
include "koneksi.php"; 

use Dompdf\Dompdf;

// Ambil data jurnal dari database
$query = mysqli_query($conn, "SELECT tanggal, jam, uap_masuk, uap_bekas, tekanan_oli, cooling_water, voltase, ampere, kw, frekuensi FROM jurnal ORDER BY id_jurnal DESC LIMIT 24");
$dataJurnal = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Siapkan path absolut gambar logo (HARUS BASE64 untuk DOMPDF)
$logoPath = realpath('../images/logo_perkebunan_nusantara.png');
if (!file_exists($logoPath)) {
    die("Gambar tidak ditemukan: $logoPath");
}
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoData;

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->setPaper('A4', 'landscape');

// Mulai isi HTML
$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 7px; margin: 5mm; }
    table { border-collapse: collapse; width: 100%; table-layout: fixed; }
    td, th { border: 1px solid black; padding: 1px; text-align: center; vertical-align: middle; }
    .no-border { border: none !important; }
    .header-title { text-align: center; font-weight: bold; font-size: 11px; }
    .sub-header { text-align: center; font-size: 9px; margin: 3px 0; }

    .bottom-section {
        width: 100%;
        margin-top: 5px;
        display: table;
        table-layout: fixed;
    }
    .bottom-cell {
        display: table-cell;
        vertical-align: top;
        padding: 0 2px;
    }
    .notes-box { height: 30px; border: 1px solid black; width: 100%; margin-top: 2px; }
    .signature-lines { margin-top: 20px; }
    .signature-line-text { font-size: 8px; }
    .footer-table td { font-size: 8px; border: 1px solid black; text-align: center; padding: 2px; }
</style>

<table class="no-border" style="margin-bottom: 3px;">
    <tr>
        <td class="no-border" width="10%" style="text-align: left;">
            <img src="' . $logoSrc . '" width="60" height="30">
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
            <td rowspan="3">%Sesuai</td>
            <td rowspan="3">Keterangan Kendala</td>
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

// Masukkan data dari database ke tabel PDF
foreach ($dataJurnal as $row) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars(date('H:i', strtotime($row['jam']))) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['uap_masuk']) . '</td><td>-</td>';
    $html .= '<td>' . htmlspecialchars($row['uap_bekas']) . '</td><td>-</td>';
    $html .= '<td>' . htmlspecialchars($row['tekanan_oli']) . '</td>';
    $html .= '<td>-</td><td>-</td><td>-</td><td>-</td>';
    $html .= '<td>' . htmlspecialchars($row['cooling_water']) . '</td><td>-</td>';
    $html .= '<td>' . htmlspecialchars($row['voltase']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['ampere']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['kw']) . '</td>';
    $html .= '<td>-</td>';
    $html .= '<td>' . htmlspecialchars($row['frekuensi']) . '</td>';
    $html .= '<td>-</td><td>-</td>';
    $html .= '<td></td><td></td><td></td>';
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
            <tr><td colspan="2">Digunakan dlm Standup mesin</td></tr>
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
            <tr><td colspan="2">Diketahui Oleh</td></tr>
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

// Render PDF
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("laporan_turbin.pdf", ["Attachment" => false]);
