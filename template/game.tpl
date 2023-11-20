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

    <div>

    </div>

    <form action="/index.php?action=game" method="post">
        <label for="answer">
            <input name="answer" autocomplete="off" maxlength="5" autofocus>
        </label>
        <button type="submit" name="submit">Guess</button>
    </form>

{/block}