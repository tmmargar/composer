{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="css/registrationList.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}<script src="scripts/registrationList.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>{/block}
{block name=content}{$content}{/block}