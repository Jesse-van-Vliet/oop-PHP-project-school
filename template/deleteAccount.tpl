{extends file="layout.tpl"}
{block name="content"}
    {if isset($smarty.session.user)}


        <form class="w-50" action="/index.php?action=delete" method="post">

            <h1>logout</h1>
            <div class="mb-3">
                <p class="p-0 m-0">Are you sure you want to delete your account? signed in as: {$smarty.session.user->getName()} </p>
            </div>
            <button type="submit" class="btn btn-primary">Delete</button>
            <a class="btn" href="/index.php?action=dashboard">keep account delete account</a>
        </form>
    {/if}

    {if !isset($smarty.session.user)}
        <P class="alert alert-danger">You are not signed in</P>
    {/if}

{/block}