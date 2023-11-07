{extends file="layout.tpl"}
{block name="content"}
    <form method="POST" action="/index.php?action=game">
        <button type="submit" name="startGame" value="medium">Play game</button>
    </form>
{/block}