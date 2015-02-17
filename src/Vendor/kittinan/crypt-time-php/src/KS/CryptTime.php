<?php
/*
 * CryptTime
 * Author: Kittinan
 * Description: Simple Wrapper PHP Class For Encrypt String with timeout
 * 
 * Encryption : AES128 + PKCS7
 * 
 * 
 */
namespace KS;

class CryptTime {
  
  public static $instance;
  
  private $IV = '0000000000000000';
  private $KEY = '00000000000000000000000000000000';
  private $SEPERATOR = 'XXXXX_XXXXX';
  
  
  function __construct() {
    
  }
  
  public static function getInstance() {
    if (empty(\KS\CryptTime::$instance)) {
      \KS\CryptTime::$instance = new \KS\CryptTime();
    }
    return  \KS\CryptTime::$instance;
  }
  
  public function setIV($iv) {
    $IVLength = strlen($iv);
    
    if ($IVLength > 16) {
      $iv = substr($iv, 0, 16);
    }else if ($IVLength < 16) {
      $iv = str_pad($iv, 16, '0', STR_PAD_RIGHT);
    }
    
    $this->IV = $iv;
  }
  
  public function getIV() {
    return $this->IV;
  }
  
  public function setKey($key) {
    $keyLength = strlen($key);
    
    if (empty($key)) {
      $key = '00000000000000000000000000000000';
    } else if ($keyLength > 32) {
      $key = substr($key, 0, 32);
    }else if ($keyLength < 32) {
      $key = str_pad($key, 32, '0', STR_PAD_RIGHT);
    }
    
    $this->KEY = $key;
  }
  
  public function getKey() {
    return $this->KEY;
  }
  
  public function encrypt($plainText, $timeout = 86400) {
    $endTime = time() + $timeout;
    $str = rand(0x112, 0xDEADC0DE).$this->SEPERATOR.$endTime.$this->SEPERATOR.$plainText;
    return $this->base64url_encode($this->_encryptAES128($this->IV, $this->KEY, $str));
  }
  
  public function decrypt($cipherText) {
    $str = $this->_decryptAES128($this->IV, $this->KEY, $this->base64url_decode($cipherText));
    
    $list = explode($this->SEPERATOR, $str);
    
    if (count($list) < 3) {
      //Can't Parse String
      return false;
    }
    
    list($randNum, $endTime, $plainText) = $list;
    $now = time();
    
    if ($now > $endTime) {
      //Overdue
      return false;
    }
    
    return $plainText;
  }
  
  private function _encryptAES128($iv, $key, $plain_text) {
    $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
    $blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $plain_text = $this->pkcs7_pad($plain_text, $blocksize);
    mcrypt_generic_init($cipher, $key, $iv);
    $cipher_text = mcrypt_generic($cipher, $plain_text);
    mcrypt_generic_deinit($cipher);
    mcrypt_module_close($cipher);
    return $cipher_text;
  }

  private function _decryptAES128($iv, $key, $cipher_text) {
    $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
    mcrypt_generic_init($cipher, $key, $iv);
    $plain_text = mdecrypt_generic($cipher, $cipher_text);
    $plain_text = $this->pkcs7_unpad($plain_text);
    mcrypt_generic_deinit($cipher);
    mcrypt_module_close($cipher);
    return $plain_text;
  }

  private function pkcs7_pad($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
  }

  private function pkcs7_unpad($text) {
    $pad = ord($text{strlen($text) - 1});
    if ($pad > strlen($text))
      return false;
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
      return false;
    return substr($text, 0, -1 * $pad);
  }
  
  private function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  private function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
  }
  
}
