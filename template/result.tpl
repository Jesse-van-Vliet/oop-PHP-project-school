{extends file="layout.tpl"}
{block name="content"}

    {if isset($gameSucces)}
        <div class="alert alert-success" role="alert">
        {$gameSucces}
        </div>
    {/if}

    {if isset($gameError)}
        <div class="alert alert-danger" role="alert">
        {$gameError}
        </div>
    {/if}





{/block}