<?php

class SmartyWrap {
  private static $theSmarty = null;
  private static $cssFiles = array();
  private static $jsFiles = array();

  static function init() {
    self::$theSmarty = new Smarty();
    self::$theSmarty->template_dir = Util::$rootPath . '/templates';
    self::$theSmarty->compile_dir = Util::$rootPath . '/templates_c';
    self::assign('availableLocales', Util::$availableLocales);
    self::assign('availableLang',Util::$availableLang);
    self::assign('wwwRoot', Util::$wwwRoot);
    self::assign('user', Session::getUser());
    self::addCss('bootstrap', 'main');
    self::addJs('jquery', 'bootstrap','cookies');
  }

  static function registerPlugin($type, $name, $callback) {
    self::$theSmarty->registerPlugin($type, $name, $callback);
  }

  static function assign($name, $value) {
    self::$theSmarty->assign($name, $value);
  }

  static function fetch($templateName) {
    return self::$theSmarty->fetch($templateName);
  }

  static function fetchEmail($templateName) {
    $result = self::$theSmarty->fetch('email/' . $templateName);
    return str_replace("\n", "\r\n", $result); // Acording to specs
  }

  static function display($template) {
    $baseName = pathinfo($template)['filename'];

    // Add {$template}.js if the file exists
    $jsFile = $baseName . '.js';
    $fileName = Util::$rootPath . '/www/js/' . $jsFile;
    if (file_exists($fileName)) {
      self::$jsFiles[] = $jsFile;
    }

    // Add {$template}.css if the file exists
    $cssFile = $baseName . '.css';
    $fileName = Util::$rootPath . '/www/css/' . $cssFile;
    if (file_exists($fileName)) {
      self::$cssFiles[] = $cssFile;
    }

    self::assign('cssFiles', self::$cssFiles);
    self::assign('jsFiles', self::$jsFiles);
    self::assign('flashMessages', FlashMessage::getMessages());
    self::$theSmarty->display($template);
  }

  static function addCss(/* Variable-length argument list */) {
    // Note the priorities. This allows files to be added in any order, regardless of dependencies
    foreach (func_get_args() as $id) {
      switch ($id) {
        case 'bootstrap':
          self::$cssFiles[1] = 'bootstrap-3.3.6.min.css';
          self::$cssFiles[2] = 'bootstrap-theme-3.3.6.min.css';
          break;
        case 'datetime':           self::$cssFiles[3] = 'bootstrap-datetimepicker.min.css'; break;
        case 'select2':
          self::$cssFiles[4] = 'select2.min.css';
          self::$cssFiles[5] = 'select2-boostrap.css';
          break;
        case 'main':               self::$cssFiles[6] = 'main.css'; break;
        case 'jcrop':              self::$cssFiles[7] = 'jquery.Jcrop.min.css'; break;
        default:
          FlashMessage::add("Cannot load CSS file {$id}");
          Http::redirect(Util::$wwwRoot);
      }
    }
  }

  static function addJs(/* Variable-length argument list */) {
    // Note the priorities. This allows files to be added in any order, regardless of dependencies
    foreach (func_get_args() as $id) {
      switch($id) {
        case 'jquery':           self::$jsFiles[1] = 'jquery-2.2.1.min.js'; break;
        case 'bootstrap':        self::$jsFiles[2] = 'bootstrap-3.3.6.min.js'; break;
        case 'fileUpload':       self::$jsFiles[3] = 'fileUpload.js'; break;
        case 'datetime':
          self::$jsFiles[4] = 'moment.min.js';
          self::$jsFiles[5] = 'bootstrap-datetimepicker.min.js';
          break;
        case 'select2':          self::$jsFiles[6] = 'select2.min.js'; break;
	      case 'cookies':
	        self::$jsFiles[7] = 'jquery.cookie.js';
	        self::$jsFiles[8] = 'languageCookie.js';
	        break;
        case 'jcrop': self::$jsFiles[9] = 'jquery.Jcrop.min.js'; break;  
	      default:
          FlashMessage::add("Cannot load JS script {$id}");
          Http::redirect(Util::$wwRoot);
      }
    }
  }
}

?>
