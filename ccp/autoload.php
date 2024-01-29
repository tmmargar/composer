<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes;
/**
 *
 * @param string $className
 *          Class or Interface name automatically
 *          passed to this function by the PHP Interpreter
 */
// spl_autoload_register(function($class_name) {
function CcpAutoload($class_name) {
  $class_name = str_replace(search: "\\", replace: "/", subject: $class_name);
  $rootDir = $_SERVER['HTTP_HOST'] == "www.chipchairprayer.com" ? "/home/chipch5/public_html/new/" : "";
  $dirs = array($rootDir,$rootDir . "src/classes/entity/",$rootDir . "src/classes/test/",$rootDir . "src/classes/common/PHPMailer/",$rootDir . "src/classes/common/Psr/",$rootDir . "src/classes/common/smarty/",$rootDir . "src/classes/common/smarty/libs/",$rootDir . "src/classes/utility/",$rootDir . "src/classes/utility/test/");
  foreach ($dirs as $dir) {
    // added ../ to support rest subfolder
    echo "<br>namespace CCP ../ --> " . $class_name . " -> " . $dir . str_replace("poker\ccp/", "../", $class_name) . ".php";
    if (file_exists(filename: $dir . str_replace("poker\ccp/", "../", $class_name) . ".php")) {
      echo "<br> found it CCP namespace 1";
      require_once ($dir . str_replace("poker\ccp/", "", $class_name) . ".php");
      return true;
    }
    echo "<br>namespace CCP --> " . $class_name . " -> " . $dir . str_replace("poker\ccp/", "", $class_name) . ".php";
    if (file_exists(filename: $dir . str_replace("poker\ccp/", "", $class_name) . ".php")) {
      echo "<br> found it CCP namespace 2";
      require_once ($dir . str_replace("poker\ccp/", "", $class_name) . ".php");
      return true;
    }
    // added ../ to support rest subfolder
    // echo "<br>namespace other ../ --> " . $class_name . " -> ../" . $dir . $class_name . ".php";
    if (file_exists(filename: "../" . $dir . $class_name . ".php")) {
      // echo "<br> found it CCP namespace 3";
      require_once ($dir . str_replace("poker\ccp/", "", $class_name) . ".php");
      return true;
    }
    echo "<br>namespace other --> " . $class_name . " -> " . $dir . $class_name . ".php";
    if (file_exists(filename: $dir . $class_name . ".php")) {
      echo "<br> found it other namespace 4";
      require_once ($dir . $class_name . ".php");
      return true;
    }
  }
}
spl_autoload_register("\Poker\Ccp\classes\CcpAutoload");