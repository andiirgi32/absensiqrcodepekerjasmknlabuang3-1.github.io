<?php
include "koneksi.php";
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:../login.php");
} else if (!isset($_SESSION['kodeakses'])) {
    header("Location:../login_akses.php");
} else if (isset($_SESSION['userid']) && $_SESSION['role'] != "Admin" && $_SESSION['role'] != "STAF") {
    header("Location:index.php");
}

// Load TCPDF library
require_once("tcpdf/tcpdf.php");

// Create new PDF document
$pdf = new TCPDF('l', 'mm', 'F4', true, 'UTF-8', true);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->setMargins(3, 3, 3);
$pdf->setAutoPageBreak(true, 1);

// Add a page
$pdf->AddPage();

// Set font
$pdf->setFont('helvetica', '', 12);

$tanggal = $_POST['tanggal'];
$outprintid = $_POST['outprintid'];

// Define the month number
$month_number = (int)date('m', strtotime($tanggal));

// Define the array of month names
$bulan = array(
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
);

// Get the month name
$bulan_name = $bulan[$month_number];

// Query to get the outprint name
$sql = "SELECT * FROM outprint WHERE outprintid = '$outprintid'";
$result = mysqli_query($conn, $sql);
$namaoutprint = '';
while ($row = mysqli_fetch_assoc($result)) {
    $namaoutprint = $row['outprintas'];
}

$namawali = "-"; // Nilai default
$sql_kepala_sekolah = mysqli_query($conn, "SELECT * FROM pekerja,jabatan WHERE pekerja.jabatanid=jabatan.jabatanid AND jabatan.jabatan='KEPALA SEKOLAH'");
$data_kepala_sekolah = mysqli_fetch_array($sql_kepala_sekolah);
$namaKepalaSekolah = $data_kepala_sekolah['nama'];
$nipKepalaSekolah = $data_kepala_sekolah['nip'];

function fetch_data()
{
    $output = '';
    $conn = mysqli_connect("localhost", "root", "", "absensiqrcodepekerjasmknlabuang");
    date_default_timezone_set('Asia/Makassar');
    $tanggal = $_POST['tanggal'];
    $outprintid = $_POST['outprintid'];

    $sql = "SELECT pekerja.*, pangkat.*, golongan.*, absen.*, jabatan.*
    FROM pekerja 
    INNER JOIN absen ON pekerja.nip = absen.nip 
    INNER JOIN pangkat ON pekerja.pangkatid = pangkat.pangkatid 
    INNER JOIN golongan ON pekerja.golonganid = golongan.golonganid 
    INNER JOIN jabatan ON pekerja.jabatanid = jabatan.jabatanid
    INNER JOIN outprint ON pekerja.outprintid = outprint.outprintid
    WHERE absen.tanggal = '$tanggal' AND pekerja.outprintid = '$outprintid'
    ORDER BY CASE jabatan.jabatan
            WHEN 'Kepala Sekolah' THEN 1
            WHEN 'Guru' THEN 2
            WHEN 'Kepala Tata Usaha' THEN 3
            WHEN 'STAF' THEN 4
            ELSE 5
            END, pekerja.nama ASC";
    $result = mysqli_query($conn, $sql);

    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $tanggal_dari_database = $row['tanggal'];
        $tanggal_baru = date('d-m-Y', strtotime($tanggal_dari_database));

        $waktu_datang = ($row['waktudatang'] != '00:00:00') ? $row['waktudatang'] . ' WITA<br>' . $row['keterangandatang'] : '-';
        $waktu_pulang = ($row['waktupulang'] != '00:00:00') ? $row['waktupulang'] . ' WITA<br>' . $row['keteranganpulang'] : '-';

        $output .= '
        <tr>
            <td align="center">' . $no++ . '.</td>
            <td align="left">' . $row['nama'] . '<br>NIP: ' . $row['nip'] . '</td>
            <td align="left">' . $row['pangkat'] . '/' . $row['golongan'] . '</td> 
            <td align="center">' . $row['jabatan'] . '</td>
            <td align="center">' . $row['jk'] . '</td> 
            <td align="center">' . $waktu_datang . '</td> 
            <td align="center">' . $waktu_pulang . '</td> 
        </tr>';
    }
    return $output;
}

$content = '
<table border="1" style="padding-top: 5px; padding-bottom: 5px;">
<tr bgcolor="skyblue">
    <th align="center" width="30px"><b>No</b></th>
    <th align="center" width="188px"><b>Nama Pekerja / NIP</b></th>
    <th align="center" width="170px"><b>Pangkat / Golongan</b></th>
    <th align="center" width="160px"><b>Jabatan</b></th>
    <th align="center" width="90px"><b>Jenis Kelamin</b></th>
    <th align="center" width="135px"><b>Waktu Datang</b></th>
    <th align="center" width="135px"><b>Waktu Pulang</b></th>
</tr>';

$content .= fetch_data(); // Menggunakan output dari fetch_data()
$content .= '
</table>
<div style="width: 100%;"><br><br><br><br><br><br></div>'; // Tambahkan div sebagai pemisah

$header = '
<table width="100%">
    <tr>
        <td width="10%" align="center"><img src="logo/smkn_labuang.jpg" width="65px"></td>
        <td width="80%" align="center" style="font-size: 13px;">
            <br><b>PEMERINTAH PROVINSI SULAWESI BARAT<br>DINAS PENDIDIKAN DAN KEBUDAYAAN<br>UPTD SMK NEGERI LABUANG<br><font style="font-weight: normal;">Jl. Poros Majene, Laliko, Campalagian, Kabupaten Polewali Mandar, Sulawesi Barat 91353, Indonesia</font></b>
        </td>
        <td width="10%" align="center"><img src="logo/logo-provinsi-sulawesi-barat.jpg" width="65px"></td>
    </tr>
</table>
<hr style="height: 2px;">
<table style="padding: 12px 0 8px;" width="100%">
    <tr>
        <td align="center" style="font-size: 14px;"><u><b>DAFTAR HADIR  ' . $namaoutprint . '</b></u></td>
    </tr>
</table>
<table style="padding: 2px 0 8px; font-weight: bold;">
    <tr>
        <td width="30px"></td>
        <td width="70px">Bulan</td>
        <td>: ' . $bulan_name . '</td>
    </tr>
    <tr>
        <td></td>
        <td>Tanggal</td>
        <td>: ' .  date('d-m-Y', strtotime($tanggal)) . '</td>
    </tr>
</table>';

// Array hari dalam Bahasa Indonesia
$hari = array(
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => 'Jumat',
    6 => 'Sabtu',
    7 => 'Minggu'
);

// Array bulan dalam Bahasa Indonesia
$bulan = array(
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
);

$timestamp = strtotime($tanggal);
$day = date('j', $timestamp);
$month_number = (int)date('n', $timestamp);
$year = date('Y', $timestamp);

// Mendapatkan hari berdasarkan tanggal
$day_of_week = date('N', $timestamp);

$footer = '
<div style="width: 100%;"><br><br><br><br></div> <!-- Tambahkan jarak dari tabel -->
<table width="100%">
    <tr>
        <td width="80%"></td>
        <td width="20%" align="left">Labuang, ' . $hari[$day_of_week] . ', ' . $day . ' ' . $bulan[$month_number] . ' ' . $year . '<br>Kepala UPTD SMKN Labuang<br><img src="default/ttd_kepala_sekolah_bapak_darwis.jpg" width="150px">
            <div style="border-bottom: 1px solid black;">' . $namaKepalaSekolah . '</div>NIP. ' . $nipKepalaSekolah . '
        </td>
    </tr>
</table>';

// Output the HTML content with header
$pdf->writeHTML($header . $content . $footer, true, true, true, true, '');

// Close and output PDF document
$pdf->Output('Data Abesnsi Pekerja ' . $namaoutprint . ' ' . date('d-m-Y', strtotime($tanggal)) . ' UPTD SMK Negeri Labuang.pdf', 'I');
