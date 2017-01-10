<h2>Log in</h2>
<div class="card card-block">
<form method="post">

<label><p>
<strong>Username</strong>
<small>

<?php if(!empty($minlength=User::$data['username']['minlength'])): ?>
No less than <?=$minlength?> characters.
<?php endif; ?>

<?php if(!empty($maxlength=User::$data['username']['maxlength'])): ?>
No more than <?=$maxlength?> characters.
<?php endif; ?>

</small>
<input type="text" name="username" placeholder="Username" class="form-control">
</p></label>

</form>
</div>