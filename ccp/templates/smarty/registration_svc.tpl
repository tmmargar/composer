{extends file="base_svc.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="../css/registration.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
={block name=content}
 <form action="{$action}" method="post" id="{$formName}" name="{$formName}">
  {$content}
 </form>
{/block}