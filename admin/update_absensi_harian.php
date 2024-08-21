<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/sweetalert.css">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <link rel="icon" href="logo/smkn_labuang.png">
</head>

<body>

</body>

</html>

<?php
include "koneksi.php";
session_start();

$absen_schedule = [
    'Monday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Tuesday'   => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '19:00:00'],
    'Wednesday' => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Thursday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Friday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:15:00', 'menunggu_end' => '11:44:59', 'pulang_start' => '11:45:00', 'pulang_tepat_waktu_end' => '13:00:00', 'pulang_end' => '16:00:00'],
    'Saturday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:30:00', 'menunggu_end' => '11:59:59', 'pulang_start' => '12:00:00', 'pulang_tepat_waktu_end' => '14:00:00', 'pulang_end' => '18:00:00'],
    'Sunday'    => ['datang_start' => '00:00:00', 'datang_end' => '00:00:00', 'pulang_start' => '00:00:00', 'pulang_end' => '00:00:00'] // Tidak ada absensi di hari Minggu
];

date_default_timezone_set('Asia/Makassar');
$tanggalHariIni = date('Y-m-d');
$waktuHariIni = date("H:i:s");
$tanggal = $_POST['tanggal'];
$hari_ini = date('l', strtotime($tanggal));
$schedule = $absen_schedule[$hari_ini];

$nip = $_POST['nip'];
$waktudatang = $_POST['waktudatang'];
if ($_POST['waktudatang'] == "") {
    $waktudatang = "00:00:00";
}
// echo $waktudatang;
// $keterangandatang = $_POST['keterangandatang'];
$waktupulang = $_POST['waktupulang'];
if ($_POST['waktupulang'] == "") {
    $waktupulang = "00:00:00";
}
// // $keteranganpulang = $_POST['keteranganpulang'];
$keterangantakabsen = $_POST['keterangantakabsen'];
$keterangankhusus = $_POST['keterangankhusus'];

if (isset($_POST["tambah_ket"])) {

    $status_datang = '';
    if ($waktudatang >= $schedule['datang_start'] && $waktudatang <= $schedule['datang_end']) {
        $is_datang = true;
        if ($waktudatang >= $schedule['datang_start'] && $waktudatang <= $schedule['datang_cepat_end']) {
            $status_datang = 'Datang Cepat';
        } else if ($waktudatang >= $schedule['datang_tepat_waktu_start'] && $waktudatang <= $schedule['datang_tepat_waktu_end']) {
            $status_datang = 'Datang Tepat Waktu';
        } else {
            $status_datang = 'Datang Terlambat';
        }
    }

    $status_pulang = '';
    if ($waktupulang >= $schedule['pulang_cepat_start'] && $waktupulang <= $schedule['pulang_end']) {
        $is_pulang = true;
        if ($waktupulang >= $schedule['pulang_cepat_start'] && $waktupulang <= $schedule['menunggu_end']) {
            $status_pulang = 'Pulang Cepat';
        } else if ($waktupulang >= $schedule['pulang_start'] && $waktupulang <= $schedule['pulang_tepat_waktu_end']) {
            $status_pulang = 'Pulang Tepat Waktu';
        } else {
            $status_pulang = 'Pulang Terlambat';
        }
    }

    // Tanggal Sabtu mendatang, Sabtu sebelumnya, atau Sabtu minggu lalu  && $waktuHariIni >= "09:00:00"
    if ($tanggalHariIni >= $tanggal) {

        if ($waktudatang != "00:00:00" && $waktupulang != "00:00:00" && $keterangantakabsen != "") {
            // $_SESSION['message'] = [
            //     'type' => 'error',
            //     'text' => 'Dikarenakan Data Keterangan Tidak Absen Terisi, Waktu Datang dan Waktu Pulang Terisi Juga. Pastika jika Keterangan Tidak Absen DiIsi Pastikan Waktu Datang atau Waktu Pulang Tidak Terisi. Jika Waktu Datang dan Waktu Pulang Terisi Pastikan Keterangan Tidak Absen Berada di status "Tidak Memiliki Keterangan"'
            // ];
            echo "<script type='text/javascript'>
            setTimeout(function () { 
              swal({
                      title: 'Data Gagal Diubah',
                      text:  'Karena Data Ket. Tidak Absen Terisi, Waktu Datang dan Waktu Pulang Terisi Juga!',
                      type: 'error',
                      timer: 3000,
                      showConfirmButton: true
                  });   
            });  
            window.setTimeout(function(){ 
              window.history.go(-1);
            } ,900); 
            </script>";
            return false;
        } else {
            // Siapkan query SQL untuk menambah data
            $stmt = $conn->prepare("INSERT INTO absen (nip, tanggal, waktudatang, keterangandatang, waktupulang, keteranganpulang, keterangantakabsen, keterangankhusus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $nip, $tanggal, $waktudatang, $status_datang, $waktupulang, $status_pulang, $keterangantakabsen, $keterangankhusus);

            if ($stmt->execute()) {
                echo "<script type='text/javascript'>
           setTimeout(function () { 
             swal({
                     title: 'Data Berhasil Diubah',
                     text:  'Data Segera Ditampilkan!',
                     type: 'success',
                     timer: 3000,
                     showConfirmButton: true
                 });   
           });  
           window.setTimeout(function(){ 
             window.history.go(-1);
           } ,900); 
           </script>";
            } else {
                echo "<script type='text/javascript'>
           setTimeout(function () { 
             swal({
                     title: 'Data Gagal Diubah',
                     text:  'Silahkan Coba Lagi!',
                     type: 'error',
                     timer: 3000,
                     showConfirmButton: true
                 });   
           });  
           window.setTimeout(function(){ 
             window.history.go(-1);
           } ,900); 
           </script>" . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "<script type='text/javascript'>
               setTimeout(function () { 
                 swal({
                         title: 'Data Gagal Diubah',
                         text:  'Pembaruan tidak diperbolehkan khusus data Besok Hari dan Seterusnya.',
                         type: 'warning',
                         timer: 3000,
                         showConfirmButton: true
                     });   
               });  
               window.setTimeout(function(){ 
                 window.history.go(-1);
               } ,900); 
               </script>";
        return false;
    }
} else if (isset($_POST["update_ket"])) {

    $status_datang = '';
    if ($waktudatang >= $schedule['datang_start'] && $waktudatang <= $schedule['datang_end']) {
        $is_datang = true;
        if ($waktudatang >= $schedule['datang_start'] && $waktudatang <= $schedule['datang_cepat_end']) {
            $status_datang = 'Datang Cepat';
        } else if ($waktudatang >= $schedule['datang_tepat_waktu_start'] && $waktudatang <= $schedule['datang_tepat_waktu_end']) {
            $status_datang = 'Datang Tepat Waktu';
        } else {
            $status_datang = 'Datang Terlambat';
        }
    }

    $status_pulang = '';
    if ($waktupulang >= $schedule['pulang_cepat_start'] && $waktupulang <= $schedule['pulang_end']) {
        $is_pulang = true;
        if ($waktupulang >= $schedule['pulang_cepat_start'] && $waktupulang <= $schedule['menunggu_end']) {
            $status_pulang = 'Pulang Cepat';
        } else if ($waktupulang >= $schedule['pulang_start'] && $waktupulang <= $schedule['pulang_tepat_waktu_end']) {
            $status_pulang = 'Pulang Tepat Waktu';
        } else {
            $status_pulang = 'Pulang Terlambat';
        }
    }

    // Tanggal Sabtu mendatang, Sabtu sebelumnya, atau Sabtu minggu lalu
    if ($tanggalHariIni >= $tanggal) {

        if ($waktudatang != "00:00:00" && $waktupulang != "00:00:00" && $keterangantakabsen != "") {
            // $_SESSION['pesan'] = [
            //     'type' => 'error',
            //     'text' => 'Dikarenakan Data Keterangan Tidak Absen Terisi, Waktu Datang dan Waktu Pulang Terisi Juga. Pastika jika Keterangan Tidak Absen DiIsi Pastikan Waktu Datang atau Waktu Pulang Tidak Terisi. Jika Waktu Datang dan Waktu Pulang Terisi Pastikan Keterangan Tidak Absen Berada di status "Tidak Memiliki Keterangan"'
            // ];
            echo "<script type='text/javascript'>
            setTimeout(function () { 
              swal({
                      title: 'Data Gagal Diubah',
                      text:  'Karena Data Ket. Tidak Absen Terisi, Waktu Datang dan Waktu Pulang Terisi Juga!',
                      type: 'error',
                      timer: 3000,
                      showConfirmButton: true
                  });   
            });  
            window.setTimeout(function(){ 
              window.history.go(-1);
            } ,900); 
            </script>";
            return false;
        } else {
            $id = $_POST['id'];
            // Siapkan query SQL untuk menambah data
            $stmt = $conn->prepare("UPDATE absen SET 
    nip = ?, 
    tanggal = ?, 
    waktudatang = ?, 
    keterangandatang = ?, 
    waktupulang = ?, 
    keteranganpulang = ?, 
    keterangantakabsen = ?, 
    keterangankhusus = ? 
WHERE id = ?");
            $stmt->bind_param('sssssssss', $nip, $tanggal, $waktudatang, $status_datang, $waktupulang, $status_pulang, $keterangantakabsen, $keterangankhusus, $id);


            if ($stmt->execute()) {
                echo "<script type='text/javascript'>
           setTimeout(function () { 
             swal({
                     title: 'Data Berhasil Diubah',
                     text:  'Data Segera Ditampilkan!',
                     type: 'success',
                     timer: 3000,
                     showConfirmButton: true
                 });   
           });  
           window.setTimeout(function(){ 
             window.history.go(-1);
           } ,900); 
           </script>";
            } else {
                echo "<script type='text/javascript'>
           setTimeout(function () { 
             swal({
                     title: 'Data Gagal Diubah',
                     text:  'Silahkan Coba Lagi!',
                     type: 'error',
                     timer: 3000,
                     showConfirmButton: true
                 });   
           });  
           window.setTimeout(function(){ 
             window.history.go(-1);
           } ,900); 
           </script>" . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        echo "<script type='text/javascript'>
               setTimeout(function () { 
                 swal({
                         title: 'Data Gagal Diubah',
                         text:  'Pembaruan tidak diperbolehkan khusus data Besok Hari dan Seterusnya.',
                         type: 'warning',
                         timer: 3000,
                         showConfirmButton: true
                     });   
               });  
               window.setTimeout(function(){ 
                 window.history.go(-1);
               } ,900); 
               </script>";
        return false;
    }
}
?>