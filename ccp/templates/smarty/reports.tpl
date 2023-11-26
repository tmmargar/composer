{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="css/reports.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}<script src="scripts/reports.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>{/block}
{block name=content}
 <form action="{$action}" method="post" id="{$formName}" name="{$formName}">
  {$content}
 </form>
 {$contentDialog}
{/block}