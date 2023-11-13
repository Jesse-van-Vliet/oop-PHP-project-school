{extends file="layout.tpl"}
{block name="content"}
    {$smarty.session.user}

    {foreach from=$smarty.session.users item=users}
        <p></p>
    {/foreach}
{/block}