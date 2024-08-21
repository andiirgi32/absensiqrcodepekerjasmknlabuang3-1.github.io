<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="admin/css/sweetalert.css">
  <script src="admin/js/jquery-2.1.4.min.js"></script>
  <script src="admin/js/sweetalert.min.js"></script>
  <link rel="icon" href="admin/logo/smkn_labuang.png">
</head>

<body>

</body>

</html>

<?php

require 'admin/koneksi.php';

// Menentukan waktu absensi berdasarkan hari
$absen_schedule = [
  'Monday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
  'Tuesday'   => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '11:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
  'Wednesday' => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
  'Thursday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
  'Friday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:15:00', 'menunggu_end' => '11:44:59', 'pulang_start' => '11:45:00', 'pulang_tepat_waktu_end' => '13:00:00', 'pulang_end' => '16:00:00'],
  'Saturday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:30:00', 'menunggu_end' => '11:59:59', 'pulang_start' => '12:00:00', 'pulang_tepat_waktu_end' => '14:00:00', 'pulang_end' => '16:00:00'],
  'Sunday'    => ['datang_start' => '00:00:00', 'datang_end' => '00:00:00', 'pulang_start' => '00:00:00', 'pulang_end' => '00:00:00'] // Tidak ada absensi di hari Minggu
];

// Memeriksa apakah NIS dan tanggal tersedia dalam POST
if (isset($_POST["nip"], $_POST["tanggal"])) {

  $nip = $_POST["nip"];
  $image = $_POST['image'];
  date_default_timezone_set('Asia/Makassar');
  $tanggal = $_POST["tanggal"];

  // Mendapatkan waktu saat ini dengan zona waktu Asia/Makassar
  $waktu = date("H:i:s");
  $hari_ini = date("l");

  // Mendapatkan jadwal absensi berdasarkan hari ini
  $schedule = $absen_schedule[$hari_ini];

  // Menentukan status absensi datang dan pulang
  $status_datang = '';
  $status_pulang = '';
  $is_datang = false;
  $is_pulang = false;

  if ($hari_ini == "Sunday") {
    $message = "Maaf Absensi Tidak Dilakukan Pada Hari Minggu! Silahkan Lakukan Absensi Pada Hari Senin, Jangan Sampai Lewat Yah!";
    echo "<script type='text/javascript'>
        setTimeout(function () { 
            var utterance = new SpeechSynthesisUtterance('$message');
            var voices = window.speechSynthesis.getVoices();

            // Mengatur kecepatan bicara
            utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

            // Mengatur volume (0.1 sampai 1)
            utterance.volume = 1;

            // Mengatur pitch (0 sampai 2)
            utterance.pitch = 2;

            window.speechSynthesis.speak(utterance);
            
            swal({
                title: 'Maaf Absensi Tidak Dilakukan Pada Hari Minggu!',
                text:  'Silahkan Lakukan Absensi Pada Hari Senin, Jangan Sampai Lewat Yah!',
                type: 'error',
                timer: 3000,
                showConfirmButton: true
            });   
        }); // Menunda sedikit sebelum memulai text-to-speech
            
        window.setTimeout(function(){ 
            window.location.replace('index.php');
        }, 900); 
      </script>";
    return false;
  } else if ($waktu >= $schedule['datang_start'] && $waktu <= $schedule['datang_end']) {
    $is_datang = true;
    if ($waktu >= $schedule['datang_start'] && $waktu <= $schedule['datang_cepat_end']) {
      $status_datang = 'Datang Cepat';
    } else if ($waktu >= $schedule['datang_tepat_waktu_start'] && $waktu <= $schedule['datang_tepat_waktu_end']) {
      $status_datang = 'Datang Tepat Waktu';
    } else {
      $status_datang = 'Datang Terlambat';
    }
  } else if ($waktu >= $schedule['menunggu_start'] && $waktu <= $schedule['menunggu_end']) {
    if ($waktu >= $schedule['pulang_cepat_start'] && $waktu <= $schedule['menunggu_end']) {
      $is_pulang = true;
      $status_pulang = 'Pulang Cepat';
    } else {
      $message = "Maaf Waktu Untuk Melakukan Absensi Datang Sudah Lewat! Silahkan Lakukan Absensi Pulang Pada Pukul " . $schedule['pulang_start'] . " WITA, Jangan Sampai Lewat Yah!";
      echo "<script type='text/javascript'>
        setTimeout(function () { 
            var utterance = new SpeechSynthesisUtterance('$message');
            var voices = window.speechSynthesis.getVoices();

            // Mengatur kecepatan bicara
            utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

            // Mengatur volume (0.1 sampai 1)
            utterance.volume = 1;

            // Mengatur pitch (0 sampai 2)
            utterance.pitch = 2;

            window.speechSynthesis.speak(utterance);
            
            swal({
                title: 'Maaf Waktu Untuk Melakukan Absensi Datang Sudah Lewat!',
                text:  'Silahkan Lakukan Absensi Pulang Pada Pukul " . $schedule['pulang_start'] . " WITA, Jangan Sampai Lewat Yah!',
                type: 'error',
                timer: 3000,
                showConfirmButton: true
            });   
        }); // Menunda sedikit sebelum memulai text-to-speech
            
        window.setTimeout(function(){ 
            window.location.replace('index.php');
        }, 900); 
      </script>";
      return false;
    }
  } else if ($waktu >= $schedule['pulang_start'] && $waktu <= $schedule['pulang_end']) {
    $is_pulang = true;
    if ($waktu >= $schedule['pulang_start'] && $waktu <= $schedule['pulang_tepat_waktu_end']) {
      $status_pulang = 'Pulang Tepat Waktu';
    } else {
      $status_pulang = 'Pulang Terlambat';
    }
  } else {
    $message = "Maaf Waktu Untuk Melakukan Absensi Telah Selesai! Silahkan Lakukan Absensi Pada Besok Hari, Jangan Sampai Lewat Yah!";
    echo "<script type='text/javascript'>
        setTimeout(function () { 
            var utterance = new SpeechSynthesisUtterance('$message');
            var voices = window.speechSynthesis.getVoices();

            // Mengatur kecepatan bicara
            utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

            // Mengatur volume (0.1 sampai 1)
            utterance.volume = 1;

            // Mengatur pitch (0 sampai 2)
            utterance.pitch = 2;

            window.speechSynthesis.speak(utterance);
            
            swal({
                title: 'Maaf Waktu Untuk Melakukan Absensi Telah Selesai!',
                text:  'Silahkan Lakukan Absensi Pada Besok Hari, Jangan Sampai Lewat Yah!',
                type: 'error',
                timer: 3000,
                showConfirmButton: true
            });   
        }); // Menunda sedikit sebelum memulai text-to-speech
            
        window.setTimeout(function(){ 
            window.location.replace('index.php');
        }, 900); 
      </script>";
    return false;
  }

  // Memeriksa apakah NIP sudah ada dalam tabel pekerja
  $check_nip_query = mysqli_query($conn, "SELECT * FROM pekerja WHERE nip='$nip'");

  if (mysqli_num_rows($check_nip_query) == 0) {
    $message = "NIP tidak terdaftar! Silakan hubungi admin";
    echo "<script type='text/javascript'>
        setTimeout(function () { 
            var utterance = new SpeechSynthesisUtterance('$message');
            var voices = window.speechSynthesis.getVoices();

            // Mengatur kecepatan bicara
            utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

            // Mengatur volume (0.1 sampai 1)
            utterance.volume = 1;

            // Mengatur pitch (0 sampai 2)
            utterance.pitch = 2;

            window.speechSynthesis.speak(utterance);
            
            swal({
                title: 'NIP tidak terdaftar!',
                text:  'Silakan hubungi admin',
                type: 'error',
                timer: 3000,
                showConfirmButton: true
            });   
        }); // Menunda sedikit sebelum memulai text-to-speech
            
        window.setTimeout(function(){ 
            window.location.replace('index.php');
        }, 900); 
      </script>";
    return false;
  }

  // Memeriksa apakah NIP sudah ada dalam tabel absen untuk tanggal yang sama
  $check_absen_query = mysqli_query($conn, "SELECT * FROM absen WHERE nip='$nip' AND tanggal='$tanggal'");

  // Jika NIP sudah ada dalam tabel absen
  if (mysqli_num_rows($check_absen_query) == 1) {
    $absen_row = mysqli_fetch_assoc($check_absen_query);
    // $absen_row = mysqli_fetch_assoc(($chekck_absen_query));

    // Update absen pulang
    if ($is_pulang) {
      if ($absen_row['waktupulang'] != "00:00:00") {
        $message = "Mohon Maaf Anda Sudah Absen Pulang! Silahkan Absen Pada Besok Hari";
        echo "<script type='text/javascript'>
        setTimeout(function () { 
            var utterance = new SpeechSynthesisUtterance('$message');
            var voices = window.speechSynthesis.getVoices();

            // Mengatur kecepatan bicara
            utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

            // Mengatur volume (0.1 sampai 1)
            utterance.volume = 1;

            // Mengatur pitch (0 sampai 2)
            utterance.pitch = 2;

            window.speechSynthesis.speak(utterance);
            
            swal({
                title: 'Mohon Maaf Anda Sudah Absen Pulang!',
                text:  'Silahkan Absen Pada Besok Hari',
                type: 'warning',
                timer: 3000,
                showConfirmButton: true
            });   
        }); // Menunda sedikit sebelum memulai text-to-speech
            
        window.setTimeout(function(){ 
            window.location.replace('index.php');
        }, 900); 
      </script>";
        return false;
      } else {
        // Membuat nama file yang unik menggunakan NIP dan timestamp
        $timestamp = time();
        $directory = 'admin/fotobuktipulang/';
        $fileName = $nip . '_' . $timestamp . '.png';

        // Membuat direktori jika belum ada
        if (!is_dir($directory)) {
          mkdir($directory, 0755, true);
        }

        // Membersihkan data base64
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = base64_decode($image);

        // Menyimpan gambar ke file
        $result = file_put_contents('admin/fotobuktipulang/' . $fileName, $image);
        $fotopulang = $conn->real_escape_string($fileName);
        $update_pulang_query = "UPDATE absen SET waktupulang='$waktu', keteranganpulang='$status_pulang', fotopulang='$fotopulang' WHERE nip='$nip' AND tanggal='$tanggal'";
        $result = mysqli_query($conn, $update_pulang_query);

        $sql_nama = mysqli_query($conn, "SELECT nama FROM pekerja WHERE nip='$nip'");
        $data_nama = mysqli_fetch_array($sql_nama);
        $nama = $data_nama['nama'];
        $message = "Selamat Anda Berhasil Absen Pulang, $nama! Silahkan Pulang";

        if ($result) {
          echo "<script type='text/javascript'>
          setTimeout(function () { 
              var utterance = new SpeechSynthesisUtterance('$message');
              var voices = window.speechSynthesis.getVoices();

              // Mengatur kecepatan bicara
              utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

              // Mengatur volume (0.1 sampai 1)
              utterance.volume = 1;

              // Mengatur pitch (0 sampai 2)
              utterance.pitch = 2;

              window.speechSynthesis.speak(utterance);
              
              swal({
                  title: 'Selamat Anda Berhasil Absen Pulang, $nama!',
                  text:  'Silahkan Pulang',
                  type: 'success',
                  timer: 3000,
                  showConfirmButton: true
              });   
          }); // Menunda sedikit sebelum memulai text-to-speech
              
          window.setTimeout(function(){ 
              window.location.replace('index.php');
          }, 900); 
        </script>";
        } else {
          echo "Error: " . mysqli_error($conn);
        }
      }
    } else {
      if ($absen_row['waktudatang'] == "00:00:00") {
        // Membuat nama file yang unik menggunakan NIP dan timestamp
        $timestamp = time();
        $directory = 'admin/fotobuktidatang/';
        $fileName = $nip . '_' . $timestamp . '.png';

        // Membuat direktori jika belum ada
        if (!is_dir($directory)) {
          mkdir($directory, 0755, true);
        }

        // Membersihkan data base64
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = base64_decode($image);

        // Menyimpan gambar ke file
        $result = file_put_contents('admin/fotobuktidatang/' . $fileName, $image);
        $fotodatang = $conn->real_escape_string($fileName);

        $get_sql_pekerja = mysqli_query($conn, "SELECT * FROM absen WHERE nip='$nip' AND tanggal='$tanggal' AND waktudatang='00:00:00' AND waktupulang='00:00:00'");
        $get_data_pekerja = mysqli_num_rows($get_sql_pekerja) > 0;

        if ($get_data_pekerja) {
          $result = mysqli_query($conn, "UPDATE absen SET waktudatang='$waktu', keterangandatang='$status_datang', fotodatang='$fotodatang' WHERE nip='$nip' AND tanggal='$tanggal'");
        } else {
          $result = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, waktudatang, keterangandatang, fotodatang) VALUES ('$nip', '$tanggal', '$waktu', '$status_datang', '$fotodatang')");
        }

        $sql_nama = mysqli_query($conn, "SELECT nama FROM pekerja WHERE nip='$nip'");
        $data_nama = mysqli_fetch_array($sql_nama);
        $nama = $data_nama['nama'];
        $message = "Selamat Anda Berhasil Absen Datang, $nama! Silahkan Masuk";

        if ($result) {
          echo "<script type='text/javascript'>
              setTimeout(function () { 
                  var utterance = new SpeechSynthesisUtterance('$message');
                  var voices = window.speechSynthesis.getVoices();
  
                  // Mengatur kecepatan bicara
                  utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
  
                  // Mengatur volume (0.1 sampai 1)
                  utterance.volume = 1;
  
                  // Mengatur pitch (0 sampai 2)
                  utterance.pitch = 2;
  
                  window.speechSynthesis.speak(utterance);
                  
                  swal({
                      title: 'Selamat Anda Berhasil Absen Datang, $nama!',
                      text:  'Silahkan Masuk',
                      type: 'success',
                      timer: 3000,
                      showConfirmButton: true
                  });   
              }); // Menunda sedikit sebelum memulai text-to-speech
                  
              window.setTimeout(function(){ 
                  window.location.replace('index.php');
              }, 900); 
            </script>";
        } else {
          echo "Error: " . mysqli_error($conn);
        }
      } else {
        $message = "Mohon Maaf Anda Sudah Absen Datang! Silahkan Absen Pulang Pada Sore Hari";

        echo "<script type='text/javascript'>
              setTimeout(function () { 
                  var utterance = new SpeechSynthesisUtterance('$message');
                  var voices = window.speechSynthesis.getVoices();
  
                  // Mengatur kecepatan bicara
                  utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
  
                  // Mengatur volume (0.1 sampai 1)
                  utterance.volume = 1;
  
                  // Mengatur pitch (0 sampai 2)
                  utterance.pitch = 2;
  
                  window.speechSynthesis.speak(utterance);
                  
                  swal({
                      title: 'Mohon Maaf Anda Sudah Absen Datang!',
                      text:  'Silahkan Absen Pulang Pada Sore Hari',
                      type: 'warning',
                      timer: 3000,
                      showConfirmButton: true
                  });   
              }); // Menunda sedikit sebelum memulai text-to-speech
                  
              window.setTimeout(function(){ 
                  window.location.replace('index.php');
              }, 900); 
            </script>";
        return false;
      }
    }
  }

  // Jika NIP belum ada dalam tabel absen, lakukan pendaftaran absen
  else {
    if ($is_datang) {
      // Membuat nama file yang unik menggunakan NIP dan timestamp
      $timestamp = time();
      $directory = 'admin/fotobuktidatang/';
      $fileName = $nip . '_' . $timestamp . '.png';

      // Membuat direktori jika belum ada
      if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
      }

      // Membersihkan data base64
      $image = str_replace('data:image/png;base64,', '', $image);
      $image = base64_decode($image);

      // Menyimpan gambar ke file
      $result = file_put_contents('admin/fotobuktidatang/' . $fileName, $image);
      $fotodatang = $conn->real_escape_string($fileName);

      $get_sql_pekerja = mysqli_query($conn, "SELECT * FROM absen WHERE nip='$nip' AND tanggal='$tanggal' AND waktudatang='00:00:00' AND waktupulang='00:00:00'");
      $get_data_pekerja = mysqli_num_rows($get_sql_pekerja) > 0;

      if ($get_data_pekerja) {
        $result = mysqli_query($conn, "UPDATE absen SET waktudatang='$waktu', keterangandatang='$status_datang', fotodatang='$fotodatang' WHERE nip='$nip' AND tanggal='$tanggal'");
      } else {
        $result = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, waktudatang, keterangandatang, fotodatang) VALUES ('$nip', '$tanggal', '$waktu', '$status_datang', '$fotodatang')");
      }

      $sql_nama = mysqli_query($conn, "SELECT nama FROM pekerja WHERE nip='$nip'");
      $data_nama = mysqli_fetch_array($sql_nama);
      $nama = $data_nama['nama'];
      $message = "Selamat Anda Berhasil Absen Datang, $nama! Silahkan Masuk";

      if ($result) {
        echo "<script type='text/javascript'>
              setTimeout(function () { 
                  var utterance = new SpeechSynthesisUtterance('$message');
                  var voices = window.speechSynthesis.getVoices();
  
                  // Mengatur kecepatan bicara
                  utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
  
                  // Mengatur volume (0.1 sampai 1)
                  utterance.volume = 1;
  
                  // Mengatur pitch (0 sampai 2)
                  utterance.pitch = 2;
  
                  window.speechSynthesis.speak(utterance);
                  
                  swal({
                      title: 'Selamat Anda Berhasil Absen Datang, $nama!',
                      text:  'Silahkan Masuk',
                      type: 'success',
                      timer: 3000,
                      showConfirmButton: true
                  });   
              }); // Menunda sedikit sebelum memulai text-to-speech
                  
              window.setTimeout(function(){ 
                  window.location.replace('index.php');
              }, 900); 
            </script>";
      } else {
        echo "Error: " . mysqli_error($conn);
      }
    } else if ($is_pulang) {
      $timestamp = time();
      $directory = 'admin/fotobuktipulang/';
      $fileName = $nip . '_' . $timestamp . '.png';

      if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
      }

      $image = str_replace('data:image/png;base64,', '', $image);
      $image = base64_decode($image);

      // Menyimpan gambar ke file
      $result = file_put_contents('admin/fotobuktipulang/' . $fileName, $image);
      $fotopulang = $conn->real_escape_string($fileName);
      $result = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, waktupulang, keteranganpulang, fotopulang) VALUES ('$nip', '$tanggal', '$waktu', '$status_pulang', '$fotopulang')");

      $sql_nama = mysqli_query($conn, "SELECT nama FROM pekerja WHERE nip='$nip'");
      $data_nama = mysqli_fetch_array($sql_nama);
      $nama = $data_nama['nama'];
      $message = "Selamat Anda Berhasil Absen Pulang, $nama! Silahkan Pulang";

      echo "<script type='text/javascript'>
              setTimeout(function () { 
                  var utterance = new SpeechSynthesisUtterance('$message');
                  var voices = window.speechSynthesis.getVoices();
  
                  // Mengatur kecepatan bicara
                  utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)
  
                  // Mengatur volume (0.1 sampai 1)
                  utterance.volume = 1;
  
                  // Mengatur pitch (0 sampai 2)
                  utterance.pitch = 2;
  
                  window.speechSynthesis.speak(utterance);
                  
                  swal({
                      title: 'Selamat Anda Berhasil Absen Pulang, $nama!',
                      text:  'Silahkan Pulang',
                      type: 'success',
                      timer: 3000,
                      showConfirmButton: true
                  });   
              }); // Menunda sedikit sebelum memulai text-to-speech
                  
              window.setTimeout(function(){ 
                  window.location.replace('index.php');
              }, 900); 
            </script>";
    }
  }
} else {
  $message = "Gagal Melakukan Absensi! Data NIP dan tanggal tidak tersedia dalam permintaan.";
  echo "<script type='text/javascript'>
          setTimeout(function () { 
              var utterance = new SpeechSynthesisUtterance('$message');
              var voices = window.speechSynthesis.getVoices();

              // Mengatur kecepatan bicara
              utterance.rate = 1.85; // Sesuaikan kecepatan bicara (1 adalah kecepatan normal)

              // Mengatur volume (0.1 sampai 1)
              utterance.volume = 1;

              // Mengatur pitch (0 sampai 2)
              utterance.pitch = 2;

              window.speechSynthesis.speak(utterance);
              
              swal({
                  title: 'Gagal Melakukan Absensi!',
                  text:  'Data NIP dan tanggal tidak tersedia dalam permintaan.',
                  type: 'error',
                  timer: 3000,
                  showConfirmButton: true
              });   
          }); // Menunda sedikit sebelum memulai text-to-speech
              
          window.setTimeout(function(){ 
              window.location.replace('index.php');
          }, 900); 
        </script>";
}

?>