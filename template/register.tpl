{extends file="layout.tpl"}
{block name="content"}
    <form class="w-50" action="/index.php?action=register" method="post">
        {if isset($registerNoti)}
            <div class="alert alert-danger" role="alert">
                {$registerNoti}
            </div>
        {/if}


        <h1>Register</h1>
        <div class="mb-3">
                <label for="username" class="form-label">username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="password1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password1" id="password1">
        </div>
        <div class="mb-3">
            <label for="password2" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" name="password2" id="password2">
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
         <a class="btn" href="/index.php?action=loginForm">already have an account? Login</a>
    </form>
{/block}