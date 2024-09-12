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
    if ($pdf_blob = fopen(filename: $file_tmp, mode: "rb")) {
      $params = array($_FILES['contentsPdf']['name'], "application/pdf", $pdf_blob);
      $bt = new BlobTest();
      $bt->setName(name: $_FILES['contentsPdf']['name']);
      $bt->setBlobContents(blobContents: $pdf_blob);
      $bt->setContentType(contentType: "application.pdf");
      $entityManager->persist(entity: $bt);
      try {
          $entityManager->flush();
      } catch (Exception $e) {
          echo "<BR>" . $e->getMessage();
      }
      //       echo "blob insert successful records " . $rowCount;
      $btFind = $entityManager->find(className: "Poker\Ccp\Entity\BlobTest", id: $_FILES['contentsPdf']['name']);
      ob_start();
      ob_clean();
      header(header: "Content-type: application/pdf");
//       echo $resultList[2];
      echo $btFind->getBlobContents();
//       header('Content-Disposition: inline; filename=' . $resultList[0]);
      header(header: "Content-Disposition: inline; filename=" . $btFind->getName());
      header(header: "Content-Transfer-Encoding: binary");
      header(header: "Accept-Ranges: bytes");
      ob_end_flush();
    } else {
      //fopen() was not successful in opening the .pdf file for reading.
      echo 'Could not open the attached pdf file';
    }
  }
}