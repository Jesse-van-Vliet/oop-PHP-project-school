{extends file="layout.tpl"}
{block name="content"}
    <h1>Add a word</h1>
    
    {if isset($wordSucces)}
        <div class="alert alert-success" role="alert">
            {$wordSucces}
        </div>
    {/if}

    {if isset($wordError)}
        <div class="alert alert-danger" role="alert">
            {$wordError}
        </div>
    {/if}
    
    <form method="POST" action="/index.php?action=addWord">
        <div>
            <label for="word">Word</label>
            <input type="text" name="word" id="word" autocomplete="off" autofocus minlength="5" maxlength="5" required pattern="[A-Za-z0-9]+">
            <input type="submit" name="add" value="Add">
        </div>
    </form>

    


{/block}