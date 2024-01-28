    {extends file="layout.tpl"}
{block name="content"}
    <form class="w-50" action="/index.php?action=login" method="post">

        {if isset($logoutSucces)}
            <div class="alert alert-success" role="alert">
                {$logoutSucces}
            </div>
        {/if}

        {if isset($logoutError)}
            <div class="alert alert-danger" role="alert">
                {$logoutError}
            </div>
        {/if}

        {if isset($nameChangeSucces)}
            <div class="alert alert-success" role="alert">
                {$nameChangeSucces}
            </div>
        {/if}


        {if isset($registerSucces)}
            <div class="alert alert-success" role="alert">
                {$registerSucces}
            </div>
        {/if}


        {if isset($loginError)}
            <div class="alert alert-danger" role="alert">
                {$loginError}
            </div>
        {/if}
        <h1>Log in</h1>
        <div class="mb-3">
            <label for="username" class="form-label">username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="password1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password1" id="password1">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
         <a class="btn" href="/index.php?action=registerForm"> Don't have a account?</a>
    </form>
{/block}