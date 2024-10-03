<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require '../vendor/autoload.php'; // Include Composer's autoloader

use Stichoza\GoogleTranslate\GoogleTranslate;

class Google_translate {
    
    protected $CI;
    protected $translator;

    public function __construct() {
        $this->CI =& get_instance();
        $this->translator = new GoogleTranslate(); // Create instance of GoogleTranslate
    }

    public function translate($text, $targetLanguage) {
        return $this->translator->setSource()->setTarget($targetLanguage)->translate($text);
    }

}
