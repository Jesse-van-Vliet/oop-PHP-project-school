{extends file="layout.tpl"}
{block name="content"}
    {if isset($smarty.session.user)}

        {if isset($loginSucces)}
            <div class="alert alert-success" role="alert">
                {$loginSucces}
            </div>
        {/if}


    <h1>Account information</h1>
    <div class="p-0 m-0 d-flex flex-md-column w-25 justify-content-around m-5">
        <p class="p-0 m-0">Username: {$smarty.session.user->getName(  )} </p>
        <p class="p-0 m-0">Role: {$smarty.session.user->getAdminStatus( )} </p>
        <p class="p-0 m-0">Won games: {$smarty.session.user->getWonGames(  )} </p>
        <p class="p-0 m-0">Lost games: {$smarty.session.user->getLostGames(  )} </p>
        <p class="p-0 m-0">Current streak: {$smarty.session.user->getStreak(  )} </p>
        <p class="p-0 m-0">Longest streak: {$smarty.session.user->getLongestStreak(  )} </p>

    </div>
        <a class="btn btn-primary" href="/index.php?action=logoutForm">Logout</a>
        <a class="btn btn-primary" href="/index.php?action=deleteForm">Delete Account</a>


    {/if}

    {if !isset($smarty.session.user)}
        <P class="alert alert-danger">You are not signed in</P>
    {/if}

{/block}