<!doctype html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>{block name=title}{$title}{/block}</title>
 <link href="../images/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" type="image/png">
 <link href="../images/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
 <link href="../images/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
 <link href="../images/site.webmanifest" rel="manifest">
 <link href="../css/reset.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.3.0/css/rowGroup.dataTables.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
 <link href="../css/datatablesLocal.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">
 <link href="../css/menu.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">
 <link href="../css/display.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">
 {block name=style}{/block}
 <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.6.3.js"></script>
 <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script>
 <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
 {if !isset($jqueryLocalAdditional) or $jqueryLocalAdditional ne 'N'}
 <script src="../scripts/jqueryLocalAdditional.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>
 {/if}
 <script src="../scripts/urlSearchParams.js"></script>
 {block name=script}{/block}
</head>
<body>
 <header id="header">
  {block name=header}{$header}{/block}
 </header>
 <div id="content">
  <div class="hide" id="info">
   <div class="hide" id="errors"></div>
   <div class="hide" id="messages"></div>
  </div>
  {block name=content}{$content}{/block}
 </div>
</body>
</html>