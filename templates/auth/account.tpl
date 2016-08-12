{extends file="layout.tpl"}

{if $identities}
  <h3>{"OpenID identities"|_}</h3>

{block name=content}
<div class="container">
  <h1 class="page-header">{"My account"|_}</h1>
  <div class="row">
    <div class="col-md-5 col-sm-6 col-xs-12">
      <form action="../editAvatar" method="post" enctype="multipart/form-data">
      {include file="bits/avatar.tpl" user=$editUser}<br>
      <label for="avatarFileName">Fișier:</label>
      <input id="avatarFileName" type="file" name="avatarFileName"><br>
      <input id="avatarSubmit" type="submit" name="submit" value="Editează" disabled="disabled">
      <a href="../saveAvatar?delete=1" onclick="return confirm('{"Confirm image deletion?"|_}');">{"Delete image"|_}</a>
      <br><br>
    </form>
    </div>
    <div class="col-md-7 col-sm-6 col-xs-12 personal-info">
      <form class="form-horizontal" role="form">
        <div class="form-group">
          <label class="col-lg-3 control-label">{"Name"|_}:</label>
          <div class="col-lg-8">
            <input class="form-control" name="name" value={$editUser->name} type="text">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">{"Email"|_}:</label>
          <div class="col-lg-8">
            <input class="form-control" name="email" value={$editUser->email} type="text">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"New password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="newpassword" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"Confirm password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="newpassword2" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">{"Enter current password"|_}:</label>
          <div class="col-md-8">
            <input class="form-control" name="password" type="password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label"></label>
          <div class="col-md-8">
            <input class="btn btn-primary" name="submitButton" value="Save Changes" type="submit">
            <span></span>
            <input class="btn btn-default" value="Cancel" type="reset">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
   $('#avatarFileName').change(function() {
       var error = '';
       var allowedTypes = ['image/gif', 'image/jpeg', 'image/png'];
       if (this.files[0].size > (1 << 21)) {
           error = '{"Maximum dimension is 2MB."|_}.';
       } else if (allowedTypes.indexOf(this.files[0].type) == -1) {
           error = '{"Only jpeg,png or gif images are allowed."|_}.';
       }
       if (error) {
           $('#avatarFileName').val('');
           $('#avatarSubmit').attr('disabled', 'disabled');
           alert(error);
       } else {
           $('#avatarSubmit').removeAttr('disabled');
       }
       return false;
   });
  </script>
{/block}

