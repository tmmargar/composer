<?php
namespace Poker\Ccp\Utility;
abstract class FileUtility {
  public static function getFileNames(string $directory, string $excludeFileNames): string {
    $validFileNames = array_diff(array: scandir(directory: $directory), arrays: $excludeFileNames);
    $result = "";
    foreach ($validFileNames as $fileName) {
      $name = explode(separator: ".", string: $fileName);
      $result .= "<br/><a href=\"" . (is_dir(filename: $name[0]) ? $name[0] . "\MainTest.class.php" : $fileName) . "\">" . $name[0] . "</a>";
    }
    return $result;
  }
}