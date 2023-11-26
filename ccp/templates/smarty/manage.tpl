{extends file="base.tpl"}
{block name=title}Chip Chair and a Prayer {$title}{/block}
{block name=style}<link href="css/manage.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}{$script}{/block}
{block name=content}
 <form action="{$action}" method="post" id="frmManage" name="frmManage">
  {$content}
 </form>
{/block}