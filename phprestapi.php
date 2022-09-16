<?php
require_once "koneksi.php";
if (isset($_GET['function'])) {
   $_GET['function']();
}

function get_karyawan()
{
   global $connect;
   $_POST = json_decode(file_get_contents('php://input'), true); //untuk membaca inputan di body

   if (isset($_POST['token']) and $_POST['token'] == "POSTMEMBER-" . date('Ymd')) {
      $query = $connect->query("SELECT * FROM karyawan");
      while ($row = mysqli_fetch_object($query)) {
         $data[] = $row;
      }
      $response = array(
         'status' => 1,
         'message' => 'Success',
         'data' => $data
      );
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($response);
   } else {
      echo json_encode(array("status" => 0, "error" => "Invalid Token", "data" => array()));
   }
}

function delete_karyawan()
{
   global $connect;
   $_POST = json_decode(file_get_contents('php://input'), true);

   if (isset($_POST['token']) and $_POST['token'] == "POSTMEMBER-" . date('Ymd')) {
      $query = "DELETE FROM karyawan WHERE id=" . $_POST['id'];
      if (mysqli_query($connect, $query)) {
         $response = array(
            'status' => 1,
            'message' => 'Delete Success'
         );
      } else {
         $response = array(
            'status' => 0,
            'message' => 'Delete Fail.'
         );
      }
      header('Content-Type: application/json');
      echo json_encode($response);
   } else {
      echo json_encode(array("status" => 0, "error" => "Invalid Token", "data" => array()));
   }
}

function insert_karyawan()
{
   global $connect;
   $_POST = json_decode(file_get_contents('php://input'), true);

   if (isset($_POST['token']) and $_POST['token'] == "POSTMEMBER-" . date('Ymd')) {
      $check = array('nama' => '', 'jenis_kelamin' => '', 'alamat' => '', 'umur' => '');
      $check_match = count(array_intersect_key($_POST, $check));
      if ($check_match == count($check)) {

         $result = mysqli_query($connect, "INSERT INTO karyawan SET
               nama = '$_POST[nama]',
               jenis_kelamin = '$_POST[jenis_kelamin]',
               alamat = '$_POST[alamat]',
               umur = '$_POST[umur]'");

         if ($result) {
            $response = array(
               'status' => 1,
               'message' => 'Insert Success'
            );
         } else {
            $response = array(
               'status' => 0,
               'message' => 'Insert Failed.'
            );
         }
      } else {
         $response = array(
            'status' => 0,
            'message' => 'Wrong Parameter'
         );
      }
      header('Content-Type: application/json');
      echo json_encode($response);
   } else {
      echo json_encode(array("status" => 0, "error" => "Invalid Token", "data" => array()));
   }
}

function update_karyawan()
{
   global $connect;
   $_POST = json_decode(file_get_contents('php://input'), true);
   if (!empty($_POST["id"])) {
      $id = $_POST["id"];
   }

   if (isset($_POST['token']) and $_POST['token'] == "POSTMEMBER-" . date('Ymd')) {
      $check = array('id' => '', 'nama' => '', 'jenis_kelamin' => '', 'alamat' => '', 'umur' => '');
      $check_match = count(array_intersect_key($_POST, $check));
      if ($check_match == count($check)) {
         $umurtambah = $_POST['umur'];
         $sql = "SELECT umur FROM karyawan WHERE id= $id";
         $run = $connect->query($sql);
         while ($row = mysqli_fetch_object($run)) {
            $data = $row;
         }
         $jumlah = $data->umur + $umurtambah;
         // echo json_encode($jumlah);
         $result = mysqli_query($connect, "UPDATE karyawan SET               
               nama = '$_POST[nama]',
               jenis_kelamin = '$_POST[jenis_kelamin]',
               alamat = '$_POST[alamat]',
               umur = '$jumlah' WHERE id = $id");

         if ($result) {
            $response = array(
               'status' => 1,
               'message' => 'Update Success'
            );
         } else {
            $response = array(
               'status' => 0,
               'message' => 'Update Failed'
            );
         }
      } else {
         $response = array(
            'status' => 0,
            'message' => 'Wrong Parameter',
            'data' => $_POST['id']
         );
      }
      header('Content-Type: application/json');
      echo json_encode($response);
   } else {
      echo json_encode(array("status" => 0, "error" => "Invalid Token", "data" => array()));
   }
}
