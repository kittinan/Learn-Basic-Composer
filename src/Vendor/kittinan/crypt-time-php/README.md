CryptTime-PHP
=========
[![Build Status](https://travis-ci.org/kittinan/CryptTime-PHP.svg?branch=master)](https://travis-ci.org/kittinan/CryptTime-PHP)
[![Coverage Status](https://coveralls.io/repos/kittinan/CryptTime-PHP/badge.png?branch=master)](https://coveralls.io/r/kittinan/CryptTime-PHP?branch=master)

CryptTime-PHP is a simple class to encrypt string with timeout. the encryption use AES128/PKCS7.

## Requirement
* PHP 5.3+
* php-mcrypt

## Composer
This plugin on the Packagist.

[https://packagist.org/packages/kittinan/crypt-time-php](https://packagist.org/packages/kittinan/crypt-time-php)




## Quick Start.
```php

$plainText = 'Hello World';

$cryptTime = \KS\CryptTime::getInstance();

$cipherText = $cryptTime->encrypt($plainText);  //Default timeout is 86400 seconds (1 day)

$decryptText = $cryptTime->decrypt($cipherText);

```

if you want to encrypt string with 10 minutes timeout.
```php

$plainText = 'Hello World';

$cryptTime = \KS\CryptTime::getInstance();

$cipherText = $cryptTime->encrypt($plainText, 600);  //10 minutes = 600 seconds

$decryptText = $cryptTime->decrypt($cipherText);
```

you can set IV and Key
```php

$cryptTime = \KS\CryptTime::getInstance();
$cryptTime->setIV('MyNewInitialValue');
$cryptTime->setKey('MyNewKeyMyNewKeyMyNewKey');
```



