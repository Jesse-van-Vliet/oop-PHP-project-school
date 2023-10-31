{extends file="layout.tpl"}
{block name="content"}
    {if isset($smarty.session.user)}

        {if isset($loginSucces)}
            <div class="alert alert-success" role="alert">
                {$loginSucces}
            </div>
        {/if}


    <h1>Account information</h1>
    <div class="p-0 m-0 d-flex d-flex-row w-25 justify-content-around m-5">
        <p class="p-0 m-0">Username: {$smarty.session.user} </p>
        <p class="p-0 m-0">Role: {$smarty.session.role} </p>
    </div>
        <a class="btn btn-primary" href="/index.php?action=logoutForm">Logout</a>


    {/if}

    {if !isset($smarty.session.user)}
        <P class="alert alert-danger">You are not signed in</P>
    {/if}

{/block}