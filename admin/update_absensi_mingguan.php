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
date_default_timezone_set('Asia/Makassar');
$tanggalHariIni = date('Y-m-d');
$waktuHariIni = date("H:i:s");

if (isset($_POST["tambahket"])) {
  // Ambil tanggal saat ini
  $tanggal_saat_ini = new DateTime();
  $hari_dalam_minggu = $tanggal_saat_ini->format('N');

  // Hitung tanggal Sabtu minggu ini
  $tanggal_sabtu_minggu_ini = clone $tanggal_saat_ini;
  $tanggal_sabtu_minggu_ini->modify('this Saturday');

  // Hitung tanggal Sabtu mendatang dan Sabtu sebelumnya
  if ($hari_dalam_minggu <= 6) {
    // Sabtu mendatang
    $tanggal_sabtu_mendatang = clone $tanggal_saat_ini;
    $tanggal_sabtu_mendatang->modify('next Saturday');

    // Sabtu sebelumnya
    $tanggal_sabtu_sebelumnya = clone $tanggal_sabtu_minggu_ini;
    $tanggal_sabtu_sebelumnya->modify('last Saturday');
  } else {
    // Jika hari ini hari Sabtu
    $tanggal_sabtu_mendatang = clone $tanggal_saat_ini;
    $tanggal_sabtu_mendatang->modify('next Saturday');

    // Sabtu sebelumnya
    $tanggal_sabtu_sebelumnya = clone $tanggal_sabtu_minggu_ini;
    $tanggal_sabtu_sebelumnya->modify('last Saturday');
  }

  // Format tanggal
  $tanggal_sabtu_minggu_ini_terformat = $tanggal_sabtu_minggu_ini->format('Y-m-d');
  $tanggal_sabtu_mendatang_terformat = $tanggal_sabtu_mendatang->format('Y-m-d');
  $tanggal_sabtu_sebelumnya_terformat = $tanggal_sabtu_sebelumnya->format('Y-m-d');

  // Hitung tanggal Sabtu minggu lalu
  $tanggal_sabtu_minggu_lalu = clone $tanggal_sabtu_sebelumnya;
  $tanggal_sabtu_minggu_lalu->modify('last Saturday');

  // Format tanggal Sabtu minggu lalu
  $tanggal_sabtu_minggu_lalu_terformat = $tanggal_sabtu_minggu_lalu->format('Y-m-d');

  // Ambil input dari form
  $nip = $_POST['nip'];
  $tanggal = $_POST['tanggal'];
  $keterangan_khusus = $_POST['keterangankhusus'];

  // Ambil tanggal dan waktu saat ini
  $tanggalHariIni = $tanggal_saat_ini->format('Y-m-d');
  $waktuHariIni = $tanggal_saat_ini->format('H:i:s');

  // Validasi tanggal
  if ($tanggal === $tanggal_sabtu_minggu_ini_terformat) {
    // Tanggal Sabtu minggu ini
    if ($tanggalHariIni >= $tanggal) {
      // Siapkan query SQL untuk menambah data
      $stmt = $conn->prepare("INSERT INTO absen (nip, tanggal, keterangankhusus) VALUES (?, ?, ?)");
      $stmt->bind_param('sss', $nip, $tanggal, $keterangan_khusus);

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
    } else {
      echo "<script type='text/javascript'>
              setTimeout(function () { 
                swal({
                        title: 'Data Gagal Diubah',
                        text:  'Pembaruan tidak diperbolehkan khusus data minggu ini sebelum hari Sabtu.',
                        type: 'warning',
                        timer: 3000,
                        showConfirmButton: true
                    });   
              });  
              window.setTimeout(function(){ 
                window.history.go(-1);
              } ,900); 
              </script>";
    }
  } elseif ($tanggal === $tanggal_sabtu_mendatang_terformat || $tanggal === $tanggal_sabtu_sebelumnya_terformat || $tanggal === $tanggal_sabtu_minggu_lalu_terformat) {
    // Tanggal Sabtu mendatang, Sabtu sebelumnya, atau Sabtu minggu lalu
    if ($tanggalHariIni >= $tanggal) {
      // Siapkan query SQL untuk menambah data
      $stmt = $conn->prepare("INSERT INTO absen (nip, tanggal, keterangankhusus) VALUES (?, ?, ?)");
      $stmt->bind_param('sss', $nip, $tanggal, $keterangan_khusus);

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
    } else {
      echo "<script type='text/javascript'>
              setTimeout(function () { 
                swal({
                        title: 'Data Gagal Diubah',
                        text:  'Pembaruan tidak diperbolehkan khusus data minggu depan sebelum hari Sabtu.',
                        type: 'warning',
                        timer: 3000,
                        showConfirmButton: true
                    });   
              });  
              window.setTimeout(function(){ 
                window.history.go(-1);
              } ,900); 
              </script>";
    }
  } else {
    // Tanggal tidak valid
    echo "<script type='text/javascript'>
          setTimeout(function () { 
            swal({
                    title: 'Data Gagal Diubah',
                    text:  'Pembaruan tidak diperbolehkan untuk Sabtu Depan dan Kedepannya.',
                    type: 'warning',
                    timer: 3000,
                    showConfirmButton: true
                });   
          });  
          window.setTimeout(function(){ 
            window.history.go(-1);
          } ,900); 
          </script>";
  }
} else if (isset($_POST["ubahket"])) {
  // Ambil tanggal saat ini
  $tanggal_saat_ini = new DateTime();
  $hari_dalam_minggu = $tanggal_saat_ini->format('N');

  // Hitung tanggal Sabtu minggu ini
  $tanggal_sabtu_minggu_ini = clone $tanggal_saat_ini;
  $tanggal_sabtu_minggu_ini->modify('this Saturday');

  // Hitung tanggal Sabtu mendatang dan Sabtu sebelumnya
  if ($hari_dalam_minggu <= 6) {
    // Sabtu mendatang
    $tanggal_sabtu_mendatang = clone $tanggal_saat_ini;
    $tanggal_sabtu_mendatang->modify('next Saturday');

    // Sabtu sebelumnya
    $tanggal_sabtu_sebelumnya = clone $tanggal_sabtu_minggu_ini;
    $tanggal_sabtu_sebelumnya->modify('last Saturday');
  } else {
    // Jika hari ini hari Sabtu
    $tanggal_sabtu_mendatang = clone $tanggal_saat_ini;
    $tanggal_sabtu_mendatang->modify('next Saturday');

    // Sabtu sebelumnya
    $tanggal_sabtu_sebelumnya = clone $tanggal_sabtu_minggu_ini;
    $tanggal_sabtu_sebelumnya->modify('last Saturday');
  }

  // Format tanggal
  $tanggal_sabtu_minggu_ini_terformat = $tanggal_sabtu_minggu_ini->format('Y-m-d');
  $tanggal_sabtu_mendatang_terformat = $tanggal_sabtu_mendatang->format('Y-m-d');
  $tanggal_sabtu_sebelumnya_terformat = $tanggal_sabtu_sebelumnya->format('Y-m-d');

  // Hitung tanggal Sabtu minggu lalu
  $tanggal_sabtu_minggu_lalu = clone $tanggal_sabtu_sebelumnya;
  $tanggal_sabtu_minggu_lalu->modify('last Saturday');

  // Format tanggal Sabtu minggu lalu
  $tanggal_sabtu_minggu_lalu_terformat = $tanggal_sabtu_minggu_lalu->format('Y-m-d');

  // Ambil input dari form
  $nip = $_POST['nip'];
  $tanggal = $_POST['tanggal'];
  $keterangan_khusus = $_POST['keterangankhusus'];

  // Validasi tanggal
  if ($tanggal === $tanggal_sabtu_minggu_ini_terformat) {
    // Tanggal Sabtu minggu ini
    // Siapkan query SQL untuk menambah data && $waktuHariIni >= "09:00:00"
    if ($tanggalHariIni >= $tanggal) {
      $stmt = $conn->prepare("UPDATE absen SET keterangankhusus = ? WHERE nip = ? AND tanggal = ?");
      $stmt->bind_param('sss', $keterangan_khusus, $nip, $tanggal);

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
    } else {
      echo "<script type='text/javascript'>
        setTimeout(function () { 
          swal({
                  title: 'Data Gagal Diubah',
                  text:  'Pembaruan tidak diperbolehkan khusus data minggu ini sebeluh hari Sabtu.',
                  type: 'warning',
                  timer: 3000,
                  showConfirmButton: true
              });   
        });  
        window.setTimeout(function(){ 
          window.history.go(-1);
        } ,900); 
        </script>";
    }
  } elseif ($tanggal === $tanggal_sabtu_mendatang_terformat || $tanggal === $tanggal_sabtu_sebelumnya_terformat || $tanggal === $tanggal_sabtu_minggu_lalu_terformat) {
    // Tanggal Sabtu mendatang, Sabtu sebelumnya, atau Sabtu minggu lalu
    if ($tanggalHariIni >= $tanggal) {
      $stmt = $conn->prepare("UPDATE absen SET keterangankhusus = ? WHERE nip = ? AND tanggal = ?");
      $stmt->bind_param('sss', $keterangan_khusus, $nip, $tanggal);

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
    } else {
      echo "<script type='text/javascript'>
        setTimeout(function () { 
          swal({
                  title: 'Data Gagal Diubah',
                  text:  'Pembaruan tidak diperbolehkan khusus data minggu ini sebeluh hari Sabtu.',
                  type: 'warning',
                  timer: 3000,
                  showConfirmButton: true
              });   
        });  
        window.setTimeout(function(){ 
          window.history.go(-1);
        } ,900); 
        </script>";
    }
  } else {
    // Tanggal tidak valid
    echo "<script type='text/javascript'>
        setTimeout(function () { 
          swal({
                  title: 'Data Gagal Diubah',
                  text:  'Pembaruan tidak diperbolehkan khusus data minggu depan sebeluh hari Sabtu.',
                  type: 'warning',
                  timer: 3000,
                  showConfirmButton: true
              });   
        });  
        window.setTimeout(function(){ 
          window.history.go(-1);
        } ,900); 
        </script>";
  }
} else if (isset($_POST["ketakabsen"])) {
  $tanggal = $_POST['tanggal'];
  $absenid1 = $_POST['id1'];
  $absenid2 = $_POST['id2'];
  $nip = $_POST['nip'];
  $keterangantakabsen = $_POST['keterangantakabsen'];

  if ($tanggalHariIni >= $tanggal) {
    $get_sql_ket_tak_absen = mysqli_query($conn, "SELECT * FROM absen WHERE id = '$absenid1' AND tanggal = '$tanggal' AND nip = '$nip'");
    $get_data_ket_tak_absen = mysqli_num_rows($get_sql_ket_tak_absen) > 0;

    if ($get_data_ket_tak_absen) {
      $sql_absensi = mysqli_query($conn, "UPDATE absen SET keterangantakabsen='$keterangantakabsen' WHERE id='$absenid1'");
    } else {
      $get_sql_tak_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip'");
      $get_data_tak_absen = mysqli_num_rows($get_sql_tak_absen) == 1;
      if ($get_data_tak_absen) {
        $get_sql_pp_tak_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip' AND waktudatang = '00:00:00' AND waktupulang = '00:00:00'");
        $get_data_pp_tak_absen = mysqli_num_rows($get_sql_pp_tak_absen);
        $data_pp_tak_absen = mysqli_fetch_array($get_sql_pp_tak_absen);
        $absenid3 = "";
        if ($get_data_pp_tak_absen == 1) {
          $absenid3 = $data_pp_tak_absen['id'];
        }

        if ($get_data_pp_tak_absen == 1) {
          $sql_absensi = mysqli_query($conn, "UPDATE absen SET keterangantakabsen='$keterangantakabsen' WHERE id='$absenid3'");
        } else {
          $sql_absensi = mysqli_query($conn, "UPDATE absen SET keterangantakabsen='$keterangantakabsen' WHERE id='$absenid2'");
        }
      } else {
        $sql_absensi = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, keterangantakabsen) VALUES ('$nip', '$tanggal', '$keterangantakabsen')");
      }
    }

    if ($sql_absensi) {
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
      </script>";
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
} else {

  $absen_schedule = [
    'Monday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Tuesday'   => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '19:00:00'],
    'Wednesday' => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Thursday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '13:30:00', 'menunggu_end' => '13:59:59', 'pulang_start' => '14:00:00', 'pulang_tepat_waktu_end' => '16:00:00', 'pulang_end' => '17:00:00'],
    'Friday'    => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:15:00', 'menunggu_end' => '11:44:59', 'pulang_start' => '11:45:00', 'pulang_tepat_waktu_end' => '13:00:00', 'pulang_end' => '16:00:00'],
    'Saturday'  => ['datang_start' => '06:00:00', 'datang_cepat_end' => '06:30:00', 'datang_tepat_waktu_start' => '06:30:01', 'datang_tepat_waktu_end' => '07:30:00', 'datang_end' => '09:00:00', 'menunggu_start' => '09:00:01', 'pulang_cepat_start' => '11:30:00', 'menunggu_end' => '11:59:59', 'pulang_start' => '12:00:00', 'pulang_tepat_waktu_end' => '14:00:00', 'pulang_end' => '18:00:00'],
    'Sunday'    => ['datang_start' => '00:00:00', 'datang_end' => '00:00:00', 'pulang_start' => '00:00:00', 'pulang_end' => '00:00:00'] // Tidak ada absensi di hari Minggu
  ];

  $tanggal = $_POST['tanggal'];
  $hari_ini = date('l', strtotime($tanggal));
  $schedule = $absen_schedule[$hari_ini];
  $absenid1 = $_POST['id1'];
  $absenid2 = $_POST['id2'];
  $nip = $_POST['nip'];

  if (isset($_POST["ubahdatang"])) {
    $waktudatang = $_POST['waktudatang'];
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

    if ($tanggalHariIni >= $tanggal) {
      $get_sql_tgl_absen = mysqli_query($conn, "SELECT * FROM absen WHERE id = '$absenid1' AND tanggal = '$tanggal' AND nip = '$nip'");
      $get_data_tgl_absen = mysqli_num_rows($get_sql_tgl_absen) > 0;

      if ($get_data_tgl_absen) {
        $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktudatang='$waktudatang', keterangandatang='$status_datang' WHERE id='$absenid1'");
      } else {
        $get_sql_pulang_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip'");
        $get_data_pulang_absen = mysqli_num_rows($get_sql_pulang_absen) == 1;
        if ($get_data_pulang_absen) {
          $get_sql_pp_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip' AND waktudatang = '00:00:00' AND waktupulang = '00:00:00'");
          $get_data_pp_absen = mysqli_num_rows($get_sql_pp_absen);
          $data_pp_absen = mysqli_fetch_array($get_sql_pp_absen);
          $absenid3 = "";
          if ($get_data_pp_absen == 1) {
            $absenid3 = $data_pp_absen['id'];
          }

          if ($get_data_pp_absen == 1) {
            $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktudatang='$waktudatang', keterangandatang='$status_datang' WHERE id='$absenid3'");
          } else {
            $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktudatang='$waktudatang', keterangandatang='$status_datang' WHERE id='$absenid2'");
          }
        } else {
          $sql_absensi = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, waktudatang, keterangandatang) VALUES ('$nip', '$tanggal', '$waktudatang', '$status_datang')");
        }
      }

      if ($sql_absensi) {
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
          </script>";
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
  } else if (isset($_POST["ubahpulang"])) {
    $waktupulang = $_POST['waktupulang'];
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

    if ($tanggalHariIni >= $tanggal) {
      $get_sql_tgl_absen = mysqli_query($conn, "SELECT * FROM absen WHERE id = '$absenid2' AND tanggal = '$tanggal' AND nip = '$nip'");
      $get_data_tgl_absen = mysqli_num_rows($get_sql_tgl_absen) > 0;

      if ($get_data_tgl_absen) {
        $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktupulang='$waktupulang', keteranganpulang='$status_pulang' WHERE id='$absenid2'");
      } else {
        $get_sql_pulang_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip'");
        $get_data_pulang_absen = mysqli_num_rows($get_sql_pulang_absen) == 1;
        if ($get_data_pulang_absen) {
          $get_sql_pp_absen = mysqli_query($conn, "SELECT * FROM absen WHERE tanggal = '$tanggal' AND nip = '$nip' AND waktudatang = '00:00:00' AND waktupulang = '00:00:00'");
          $get_data_pp_absen = mysqli_num_rows($get_sql_pp_absen);
          $data_pp_absen = mysqli_fetch_array($get_sql_pp_absen);
          $absenid3 = "";
          if ($get_data_pp_absen == 1) {
            $absenid3 = $data_pp_absen['id'];
          }


          if ($get_data_pp_absen == 1) {
            $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktupulang='$waktupulang', keteranganpulang='$status_pulang' WHERE id='$absenid3'");
          } else {
            $sql_absensi = mysqli_query($conn, "UPDATE absen SET waktupulang='$waktupulang', keteranganpulang='$status_pulang' WHERE id='$absenid1'");
          }
        } else {
          $sql_absensi = mysqli_query($conn, "INSERT INTO absen (nip, tanggal, waktupulang, keteranganpulang) VALUES ('$nip', '$tanggal', '$waktupulang', '$status_pulang')");
        }
      }

      if ($sql_absensi) {
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
                        type: 'warning',
                        timer: 3000,
                        showConfirmButton: true
                    });   
              });  
              window.setTimeout(function(){ 
                window.history.go(-1);
              } ,900); 
              </script>";
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
}
?>