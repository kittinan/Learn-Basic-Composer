<?php
require_once __DIR__.'/../src/KS/CryptTime.php';

/**
 * @property Crypt $Crypt
 */
class CryptTimeTest extends PHPUnit_Framework_TestCase {
  
  private $Crypt;
  
  function __construct() {
    $this->Crypt = new \KS\CryptTime();
  }
  
  public function testSetIV() {
    $iv = '';
    $this->Crypt->setIV($iv);
    $result = $this->Crypt->getIV();
    $this->assertSame('0000000000000000', $result);
    
    $iv = 'abcd';
    $this->Crypt->setIV($iv);
    $result = $this->Crypt->getIV();
    $this->assertSame('abcd000000000000', $result);
    
    $iv = 'abcdabcdabcdabcdabcdabcdabcdabcddddddddddddddddddddddddd';
    $this->Crypt->setIV($iv);
    $result = $this->Crypt->getIV();
    $this->assertSame('abcdabcdabcdabcd', $result);
  }
  
  public function testSetKey() {
    $key = '';
    $this->Crypt->setKey($key);
    $result = $this->Crypt->getKey();
    $this->assertSame('00000000000000000000000000000000', $result);
    
    $key = 'abcd';
    $this->Crypt->setKey($key);
    $result = $this->Crypt->getKey();
    $this->assertSame('abcd0000000000000000000000000000', $result);
    
    $key = 'abcdabcdabcdabcdabcdabcdabcdabcddddddddddddddddddddddddd';
    $this->Crypt->setKey($key);
    $result = $this->Crypt->getKey();
    $this->assertSame('abcdabcdabcdabcdabcdabcdabcdabcd', $result);
  }

  public function testEncrypt() {
    
    $text = 'Kittinan';
    $cipherText = $this->Crypt->encrypt($text);
    $result = $this->Crypt->decrypt($cipherText);
    $this->assertEquals($text, $result);
    
    $cipherText = $this->Crypt->encrypt($text, -1);
    $result = $this->Crypt->decrypt($cipherText);
    $this->assertEquals(false, $result);
    
    //Decrypt with incorrect key
    $cipherText = $this->Crypt->encrypt($text);
    $this->Crypt->setKey('112');
    $result = $this->Crypt->decrypt($cipherText);
    $this->assertEquals(false, $result);
  }
  
}