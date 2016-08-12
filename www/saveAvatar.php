<?php
require_once '../lib/Util.php';

$user = Session::getUser();
if (!$user) {
  Util::requireLoggedIn();
}

define('AVATAR_RESOLUTION', 160);
define('AVATAR_QUALITY', 100);

$AVATAR_REMOTE_FILE = "img/user/{$user->id}.jpg";
$AVATAR_RAW_GLOB = Util::$rootPath . "/www/img/generated/{$user->id}_raw.*";

$x0 = Request::get('x0');
$y0 = Request::get('y0');
$side = Request::get('side');
if(!$x0)
  $x0=0;
if(!$y0)
  $y0=0;
if(!$side)
  $side=160;
$delete = Request::get('delete');

if ($delete) {
  unlink($AVATAR_REMOTE_FILE);
  $user->hasAvatar = 0;
  $user->save();
  FlashMessage::add(_('Image was deleted.'), 'success');
  Http::redirect('auth/account');
}

$rawFileList = glob($AVATAR_RAW_GLOB);
if (empty($rawFileList)) {
  FlashMessage::add(_('Your profile image does not exist anymore. Please reupload it.'));
  Http::redirect('auth/account');
}

$rawFileName = $rawFileList[0];
$canvas = imagecreatetruecolor(AVATAR_RESOLUTION, AVATAR_RESOLUTION);
$image = loadImage($rawFileName);

imagecopyresampled($canvas, $image, 0, 0, $x0, $y0, AVATAR_RESOLUTION, AVATAR_RESOLUTION, $side, $side);
sharpenImage($canvas);
imagejpeg($canvas, $AVATAR_REMOTE_FILE, AVATAR_QUALITY);
unlink($rawFileName);

$user->hasAvatar = 1;
$user->save();

FlashMessage::add(_('Image was saved.'), 'success');
Http::redirect('auth/account');

/****************************************************************************/
/* Load an image by its (supported) type */
function loadImage($file) {
  $size = getimagesize($file);
  switch ($size['mime']) {
  case 'image/jpeg': return imagecreatefromjpeg($file);
  case 'image/gif': return imagecreatefromgif($file);
  case 'image/png': return imagecreatefrompng($file);
  default: return null;
  }
}
/* Sharpen an image
 * Code courtesy of http://adamhopkinson.co.uk/blog/2010/08/26/sharpen-an-image-using-php-and-gd/
 */
function sharpenImage(&$i) {
  $sharpen = array(
    array(-1.2, -1.0, -1.2),
    array(-1.0, 22.0, -1.0),
    array(-1.2, -1.0, -1.2)
  );
  $divisor = array_sum(array_map('array_sum', $sharpen));
  imageconvolution($i, $sharpen, $divisor, 0);
}
?>