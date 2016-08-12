<?php
require_once '../lib/Util.php';
$file = Util::getUploadedFile('avatarFileName');
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$error = '';

if (!$file) {
  $error = '{"You uploaded an invalid file"|_}.';
} else if ($file['error']) {
  $error = '{"An error has occured while loading"|_.}';
}

if ($error) {
  FlashMessage::add($error);
  Http::redirect(Util::$wwwRoot . 'auth/account');
}
$user = Session::getUser();
if (!$user) {
  FlashMessage::add(_('You cannot pick a profile image if you are not logged in'));
  Http::redirect(Util::$wwwRoot);
}
// Remove any old files (with different extensions)
$oldFiles = glob("img/generated/{$user->id}_raw.*");
foreach ($oldFiles as $oldFile) {
  unlink($oldFile);
}
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$destFileName = "img/generated/{$user->id}_raw.{$ext}";
if (!move_uploaded_file($file['tmp_name'], $destFileName)) {
  FlashMessage::add(_('An error has occured when copying the file.'));
  Http::redirect(Util::$wwwRoot . 'auth/account');
}
chmod($destFileName, 0666);
SmartyWrap::addJs('jcrop');
SmartyWrap::addCss('jcrop');
SmartyWrap::assign('rawFileName', "{$user->id}_raw.{$ext}");
SmartyWrap::assign('user',$user);
SmartyWrap::display('editAvatar.tpl');
?>