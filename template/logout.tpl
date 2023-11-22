{extends file="layout.tpl"}
{block name="content"}
    {if isset($smarty.session.user)}


        <form class="w-50" action="/index.php?action=logout" method="post">

            <h1>logout</h1>
            <div class="mb-3">
                <p class="p-0 m-0">Logged in as: {$smarty.session.user->getName()} </p>
            </div>
           <button type="submit" class="btn btn-primary">Logout</button>
            <a class="btn" href="/index.php?action=dashboard">Stay signed in</a>
        </form>
    {/if}

    {if !isset($smarty.session.user)}
        <P class="alert alert-danger">You are not signed in</P>
    {/if}

{/block}