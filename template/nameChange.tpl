{extends file="layout.tpl"}
{block name="content"}
    {if isset($nameChangeError)}
        <div class="alert alert-danger" role="alert">
            {$nameChangeError}
        </div>
    {/if}

 
    <h1>Change username</h1>
    <form method="POST" action="/index.php?action=nameChange">
        <div class="d-flex flex-column w-25">
            <label for="currentName">Current Username: {$smarty.session.user->getName(  )} </label>
            <label for="newName">New username: </label>
            <input type="text" name="newName" id="newName" autocomplete="off"  maxlength="45" required pattern="[A-Za-z0-9]+">
            <input type="submit" name="add" value="change name">
        </div>




{/block}