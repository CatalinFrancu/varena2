<?php

Util::init();

/**
 * This class is the only one that needs to be called explicitly from every web page or script.
 * It loads everything else on demand.
 **/
class Util {
  static $rootPath;
  static $wwwRoot;
  static $availableLocales;
  static $availableLang;

  static function init() {
    self::definePaths();
    spl_autoload_register('self::autoloadClasses');
    Config::load(self::$rootPath . "/varena.conf");
    self::setLocale();
    self::getLocales();
    $tp = self::$rootPath . '/lib/third-party'; // third-party libs
    require_once "{$tp}/idiorm/idiorm.php";
    require_once "{$tp}/idiorm/paris.php";
    require_once "{$tp}/smarty/Smarty.class.php";
    Db::init();
    Session::init();
    FlashMessage::restoreFromSession();
    SmartyWrap::init();
  }

  private static function definePaths() {
    self::$rootPath = realpath(__DIR__ . '/..');
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pos = strrpos($scriptName, '/www/');
    self::$wwwRoot = ($pos === false) ? '/' : substr($scriptName, 0, $pos + 5);
  }

  static function autoloadClasses($className) {
    $paths = ['/lib', '/lib/model', '/lib/third-party'];
    foreach ($paths as $p) {
      $fileName = self::$rootPath . "{$p}/{$className}.php";
      if (file_exists($fileName)) {
        require_once($fileName);
        return;
      }
    }
  }

  private static function getLocales(){
    $temp = Config::get('general.availableLocales');
    $languages = array();
    $locales= array();

    foreach ($temp as $t) {
      $tNew = explode("|",$t);
      array_push($languages, $tNew[0]);
      array_push($locales, $tNew[1]);
    }

    self::$availableLang = $languages;
    self::$availableLocales = $locales;
  }

  private static function setLocale() {
    mb_internal_encoding("UTF-8");
    $locale = Config::get('testing.enabled')
            ? Config::get('testing.locale')
            : Config::get('general.locale');
    if(isset($_COOKIE['locale'])){
      $localeCookie = $_COOKIE['locale'];
      //if it's the same locale erase the cookie
      if($localeCookie == $locale){
        unset($_COOKIE['locale']);
        setcookie('locale',null,-1,'/');
      } else{
        $locale = $localeCookie;
      }
    }
    setlocale(LC_ALL, $locale);
    $domain = "messages";
    bindtextdomain($domain, self::$rootPath . '/locale');
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
  }

  static function getFullServerUrl() {
    $host = $_SERVER['SERVER_NAME'];
    $port =  $_SERVER['SERVER_PORT'];
    $path = self::$wwwRoot;

    return ($port == '80') ? "http://$host$path" : "http://$host:$port$path";
  }

  static function requireNotLoggedIn() {
    if (Session::getUser()) {
      FlashMessage::add(_('You are already logged in.'), 'warning');
      Http::redirect(self::$wwwRoot);
    }
  }

  static function requireLoggedIn() {
    if (!Session::getUser()) {
      FlashMessage::add(_('Please log in to continue.'), 'warning');
      Http::redirect(self::$wwwRoot . 'auth/login');
    }
  }

  /* Notify the appropriate evaluator that a new source is available. */
  static function notifyEvaluator($source) {
    $port = Config::get('eval.port');
    $ips = Config::get('eval.ip');
    $index = $source->id % count($ips);

    $sock = socket_create(AF_INET, SOCK_STREAM, 0)
            or die("Socket create error.\n");
    socket_set_nonblock($sock);

    // Try to open a connection, but don't make a fuss if we can't.
    // The evaluator may be down at the moment.
    @socket_connect($sock, $ips[$index], $port);
    socket_close($sock);
  }

  static function getUploadedFile($name){
    return array_key_exists($name, $_FILES) ? $_FILES[$name] : null;
  }
}

?>
