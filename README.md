# smsbot-php-sdk

[status: Development]

🚀An easy-to-use Software Development Kit made in PHP for communicating with the Smsbot API.

## Installation

Download the repository or get it via Composer (recommended):

```sh
$ composer require adresser/smsbot-sdk
```



## Versions 

This package uses [semver](https://semver.org/). 

### Supported PHP Versions

This library supports the following PHP implementations:

* PHP 7.2
* PHP 7.3
* PHP 7.4



## Quickstart

* Sign up on the [smsbot](https://sms.smsbot.it) platform 
* Get your authentication key (find it on Settings section)
* To send SMSs using your device, connect the device and get the **device id** and the **SIM id**
* To send SMSs using the HTTP gateway, make a **sender ID** (go on Sender ids section)

Include the *vendor/autoload.php* file to load all the library classes. 

The **Smsbot** facade provides an ingress to the SDK. However, you can use the internal classes at your convenience. 

 

#### Send an SMS through the HTTP Gateway

> Notes: To send a **bulk sms** add more destination using the *pushDestination* method. 

```php
use Adresser\Smsbot\Sms;
use Adresser\Smsbot\Facade\Smsbot;

Smsbot::setAuthenticationKey('set the authentication key'); 

$clientFactory = Smsbot::getSmsClientFactory(); 

$client = $clientFactory->getClient('http'); 
$client->setSenderId('put the sender id'); 

// in future this will no longer be needed  
$client->setCountryId('put the country code'); 

$sms = new Sms('test message!'); 
$sms->pushDestination('+39xxxxxxxxxx'); 

$client->send($sms); 
```



#### Send an SMS through the registered device 

>  Notes: To send a **bulk sms** add more destination using the *pushDestination* method. 

```php
use Adresser\Smsbot\Sms;
use Adresser\Smsbot\Facade\Smsbot;

Smsbot::setAuthenticationKey('set the authentication key'); 

$clientFactory = Smsbot::getSmsClientFactory(); 
$client = $clientFactory->getClient('device');

$client->setDeviceId('put the device id here');
$client->setSimId('put the sim id here'); 

$sms = new Sms('test message!'); 
$sms->pushDestination('+39xxxxxxxxxx'); 

$client->send($sms); 
```



#### Generate an OTP

```php
use Adresser\Smsbot\Facade\Smsbot;

Smsbot::setAuthenticationKey('set the authentication key'); 
$factory = Smsbot::getOtpFactory(); 

$generator = $factory->getOtpGenerator(); 
$validator = $factory->getOtpValidator();  

// set the One Time Password lifetime in seconds 
$otp = $generator->generate(3600); 

// the otp will be store inside the smsbot system
if ($validator->validate($otp)) {
    echo "The OTP is valid! \n"; 
} 
```

