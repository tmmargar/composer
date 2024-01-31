<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Entity\BlobTest;
require_once "init.php";
if (isset($_POST['submit']) && !empty($_FILES['contentsPdf']['name'])) {
  //a $_FILES 'error' value of zero means success. Anything else and something wrong with attached file.
  if ($_FILES['contentsPdf']['error'] != 0) {
//     echo 'Something wrong with the file.';
  } else { //pdf file uploaded okay.
    //attached pdf file information
    $file_tmp = $_FILES['contentsPdf']['tmp_name'];
    if ($pdf_blob = fopen($file_tmp, "rb")) {
      $params = array($_FILES['contentsPdf']['name'], "application/pdf", $pdf_blob);
      $bt = new BlobTest();
      $bt->setName($_FILES['contentsPdf']['name']);
      $bt->setBlobContents($pdf_blob);
      $bt->setContentType("application.pdf");
      $entityManager->persist($bt);
      try {
          $entityManager->flush();
      } catch (Exception $e) {
          echo "<BR>" . $e->getMessage();
      }
      //       echo "blob insert successful records " . $rowCount;
      $btFind = $entityManager->find("Poker\Ccp\Entity\BlobTest", $_FILES['contentsPdf']['name']);
      ob_start();
      ob_clean();
      header('Content-type: application/pdf');
//       echo $resultList[2];
      echo $btFind->getBlobContents();
//       header('Content-Disposition: inline; filename=' . $resultList[0]);
      header('Content-Disposition: inline; filename=' . $btFind->getName());
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
      ob_end_flush();
    } else {
      //fopen() was not successful in opening the .pdf file for reading.
      echo 'Could not open the attached pdf file';
    }
  }
}