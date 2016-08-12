<img class="avatar"
  {if $user->hasAvatar}
     src="../img/user/{$user->id}.jpg?cb={1000000000|rand:9999999999}"
  {else}
     src="../img/avatar_user.png"
  {/if}
     alt="imagine de profil: {$user->username|escape}"
/>