{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="css/signup.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}<script src="scripts/signup.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>{/block}
{block name=navigation}{/block}
{block name=content}
 <form action="{$action}" method="POST" id="{$formName}" name="{$formName}">{$content}</form>
{/block}
{block name=footer}{/block}