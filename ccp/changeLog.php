<?php
declare(strict_types = 1);
namespace ccp;
require_once "init.php";
$smarty->assign("title", "Chip Chair and a Prayer Change Log");
$smarty->assign("heading", "");
$smarty->assign("style", "");
$outputChange =
  "<h1>Change Log</h1>\n" .
  "<section class=\"version\" id=\"1.3.7\">" .
  " <h3>Version 1.3.6</h3>\n" .
  " <b><time datetime=\"2024-12-10\">Dec 10, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Change jquery to load via ES module</li>\n" .
  "  <li>Hosting change related info</li>\n" .
  "  <li>Fix championship seating</li>\n" .
  "  <li>Add interface to load tournaments for a year</li>\n" .
  "  <li>Add job to change active season</li>\n" .
  "  <li>Change flags to boolean (tinyint)</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.6\">" .
  " <h3>Version 1.3.6</h3>\n" .
  " <b><time datetime=\"2024-9-19\">Sep 19, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Added auto reminder for championship</li>\n" .
  "  <li>Change jquery, datatables, font awesome to load from composer</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.5\">" .
  " <h3>Version 1.3.5</h3>\n" .
  " <b><time datetime=\"2024-9-16\">Sep 16, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Updated datatables</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.4\">" .
  " <h3>Version 1.3.4</h3>\n" .
  " <b><time datetime=\"2024-9-12\">Sep 12, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Updated composer depedencies, jQuery</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.3\">" .
  " <h3>Version 1.3.3</h3>\n" .
  " <b><time datetime=\"2024-9-11\">Sep 11, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Code cleanup add parameter names</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.2\">" .
  " <h3>Version 1.3.2</h3>\n" .
  " <b><time datetime=\"2024-9-1\">Sep 1, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Add manage inventory</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.1\">" .
  " <h3>Version 1.3.1</h3>\n" .
  " <b><time datetime=\"2024-8-18\">Aug 18, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Change summary report to show championship qualification in # column</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.3.0\">" .
  " <h3>Version 1.3.0</h3>\n" .
  " <b><time datetime=\"2024-2-27\">Feb 27, 2024</time></b>\n" .
  " <ul>\n" .
  "  <li>Change to use ORM for database connectivity</li>\n" .
  "  <li>Fix namespace and folder naming</li>\n" .
  "  <li>Fix fees report to handle hosts not paying</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.8\">" .
  " <h3>Version 1.2.8</h3>\n" .
  " <b><time datetime=\"2023-11-23\">Nov 23, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Add game type and limit type administration</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.7\">" .
  " <h3>Version 1.2.7</h3>\n" .
  " <b><time datetime=\"2023-11-14\">Nov 14, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Update email to use rich text editor (TinyMCE) for body</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.6\">" .
  " <h3>Version 1.2.6</h3>\n" .
  " <b><time datetime=\"2023-11-11\">Nov 11, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Fix delete on manage screens</li>\n" .
  "  <li>Change bonus points to be associated with season</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.5\">" .
  " <h3>Version 1.2.5</h3>\n" .
  " <b><time datetime=\"2023-11-08\">Nov 8, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Code cleanup</li>\n" .
  "  <li>Add manage tournament absences</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.4\">" .
  " <h3>Version 1.2.4</h3>\n" .
  " <b><time datetime=\"2023-10-29\">Oct 29, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Add fees</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.3\">" .
  " <h3>Version 1.2.3</h3>\n" .
  " <b><time datetime=\"2023-10-04\">Oct 4, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Change events to show season since can span multiple seasons</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.2\">" .
  " <h3>Version 1.2.2</h3>\n" .
  " <b><time datetime=\"2023-10-04\">Oct 4, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Upgrade PHP to 8.2.11</li>\n" .
  "  <li>Fix errors caused by PHP upgrade</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.1\">" .
  " <h3>Version 1.2.1</h3>\n" .
  " <b><time datetime=\"2023-10-04\">Oct 4, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Upgrade Smarty to 4.3.2</li>\n" .
  "  <li>Upgrade PHP to 8.1.24</li>\n" .
  "  <li>Fix errors caused by PHP upgrade or hidden errors</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.2.0\">" .
  " <h3>Version 1.2.0</h3>\n" .
  " <b><time datetime=\"2023-03-01\">Mar 1, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Change javascript to ES modules</li>\n" .
  "  <li>Change selectize to tom select for email\n" .
  "  <li>Change mask to non jquery version\n" .
  "  <li>Change datetimepicker to HTML5\n" .
  "  <li>Remove jquery ui\n" .
  "  <li>Change jquery and datatables to use CDN</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.1.2\">" .
  " <h3>Version 1.1.2</h3>\n" .
  " <b><time datetime=\"2023-01-17\">Jan 17, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Updated datatables, jQuery, jQuery UI and jQuery Migrate to latest versions</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.1.1\">" .
  " <h3>Version 1.1.1</h3>\n" .
  " <b><time datetime=\"2023-01-13\">Jan 13, 2023</time></b>\n" .
  " <ul>\n" .
  "  <li>Updated to be mobile friendly</li>\n" .
  "  <li>Fixed some minor bugs on maintenance screens</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.1.0\">" .
  " <h3>Version 1.1.0</h3>\n" .
  " <b><time datetime=\"2021-05-22\">May 22, 2021</time></b>\n" .
  " <ul>\n" .
  "  <li>Key tools and versions" .
  "   <ul>\n" .
  "    <li>(Database) MariaDB 10.4.17</li>\n" .
  "    <li>(Language) PHP 8.0</li>\n" .
  "    <li>(Email Library) PHP Mailer 6.2</li>\n" .
  "    <li>(Templating) Smarty 3.1.38</li>\n" .
  "    <li>(JavaScript framework) JQuery 3.5.1</li>\n" .
  "    <li>(JavaScript framework) JQuery UI 1.12.1</li>\n" .
  "    <li>(JavaScript framework) Datatables 1.10.23</li>\n" .
  "   </ul>\n" .
  "  </li>\n" .
  "  <li>Administrators ONLY\n" .
  "   <ul>\n" .
  "    <li>Added notifications</li>\n" .
  "    <li>Added payouts</li>\n" .
  "    <li>Added seasons</li>\n" .
  "    <li>Added special types (for tournaments)</li>\n" .
  "    <li>Added site down capability</li>\n" .
  "   </ul>\n" .
  "  </li>\n" .
  "  <li>Changed menu and moved to left</li>\n" .
  "  <li>Updated all user ids to be sequential</li>\n" .
  "  <li>Added foreign keys to appropriate tables</li>\n" .
  "  <li>Added notification header for system messages</li>\n" .
  " </ul>\n" .
  "</section>\n" .
  "<section class=\"version\" id=\"1.0.0\">" .
  " <h3>Version 1.0.0</h3>\n" .
  " <b><time datetime=\"2020-06-07\">June 7, 2020</time></b>\n" .
  " <ul>\n" .
  "  <li>Added championship statistics</li>\n" .
  "  <li>Added security to only allow administrators access to certain pages</li>\n" .
  "  <li>Added my profile to allow editing of their own user information</li>\n" .
  "  <li>Force HTTPS for all requests</li>\n" .
  "  <li>Administrators ONLY\n" .
  "   <ul>\n" .
  "    <li>Added manage users</li>\n" .
  "    <li>Added send email</li>\n" .
  "    <li>Added run auto register host</li>\n" .
  "    <li>Added run auto reminder</li>\n" .
  "   </ul>\n" .
  "  </li>\n" .
  " </ul>\n" .
  "</section>\n";
$smarty->assign("content", $outputChange);
$smarty->display("changeLog.tpl");