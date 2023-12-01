<?php
declare(strict_types = 1);
namespace ccp;
require_once "init.php";
// $smarty->assign("title", "Chip Chair and a Prayer Blob Test");
// $smarty->assign("heading", "Blob Test");
// $smarty->assign("action", $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"]);
// $smarty->assign("formName", "frmBlobTest");
// $output = "";
// $mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : "";
if (isset($_POST['submit']) && !empty($_FILES['contentsPdf']['name'])) {
  //a $_FILES 'error' value of zero means success. Anything else and something wrong with attached file.
  if ($_FILES['contentsPdf']['error'] != 0) {
//     echo 'Something wrong with the file.';
  } else { //pdf file uploaded okay.
    //attached pdf file information
    $file_tmp = $_FILES['contentsPdf']['tmp_name'];
    if ($pdf_blob = fopen($file_tmp, "rb")) {
      $params = array($_FILES['contentsPdf']['name'], "application/pdf", $pdf_blob);
      $rowCount = $databaseResult->insertBlob($params);
//       echo "blob insert successful records " . $rowCount;
      $resultList = $databaseResult->getBlobTest();
      ob_start();
      ob_clean();
      header('Content-type: application/pdf');
      echo $resultList[2];
      header('Content-Disposition: inline; filename=' . $resultList[0]);
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
      ob_end_flush();
    } else {
      //fopen() was not successful in opening the .pdf file for reading.
      echo 'Could not open the attached pdf file';
    }
  }
}
// $smarty->assign("content", $output);