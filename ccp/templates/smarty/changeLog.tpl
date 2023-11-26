{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}{$style}<link href="css/changeLog.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{/block}
{block name=content}{$content}{/block}