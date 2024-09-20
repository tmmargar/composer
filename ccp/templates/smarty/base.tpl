<!doctype html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>{block name=title}{$title}{/block}</title>
 <link href="images/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" type="image/png">
 <link href="images/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
 <link href="images/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
 <link href="images/site.webmanifest" rel="manifest">
 <link href="css/reset.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet" type="text/css">
 <link href="vendor/datatables.net/datatables.net-dt/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css">
 <link href="vendor/datatables.net/datatables.net-rowgroup-dt/css/rowGroup.dataTables.min.css" rel="stylesheet" type="text/css">
 <link href="vendor/components/font-awesome/css/all.css" rel="stylesheet" type="text/css">
 <link href="css/datatablesLocal.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet" type="text/css">
 <link href="css/menu.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet" type="text/css">
 <link href="css/display.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet" type="text/css">
 {block name=style}{/block}
<script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
 <script type="text/javascript" language="javascript" src="vendor/datatables.net/datatables.net/js/dataTables.min.js"></script>
 <script type="text/javascript" language="javascript" src="vendor/datatables.net/datatables.net-rowgroup/js/dataTables.rowGroup.min.js"></script>
 {if !isset($jqueryLocalAdditional) or $jqueryLocalAdditional ne 'N'}
 <script src="scripts/jqueryLocalAdditional.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>
 {/if}
 <script src="scripts/urlSearchParams.js"></script>
 <script defer src="https://unpkg.com/imask"></script>
 {block name=script}{/block}
</head>
<body>
{block name=navigation}{$navigation}{/block}
 <header id="header">
  {block name=header}{$header}{/block}
 </header>
 <div id="content">
  <h1>{$heading}</h1>
  <div id="modeDisplay">Mode: {$mode}</div>
  <div class="hide" id="info">
   <div class="hide" id="errors"></div>
   <div class="hide" id="messages"></div>
  </div>
  {block name=content}{$content}{/block}
 </div>
 {block name=footer}<footer class="footer"><a href="changeLog.php" target="_blank">Change Log</a></footer>{/block}
</body>
</html>