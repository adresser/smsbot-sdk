# smsbot-php-sdk

🚀An easy-to-use Software Development Kit made in PHP 7.4 for communicating with the Smsbot API.

## Installation

(Currently not on packagist)

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

#### Send an SMS

```php
use Adresser\Smsbot\Sms;
use Adresser\Smsbot\SmsClientFactory;

$authKey = 'put your auth key here'; 
$factory = new SmsClientFactory(); 
$client = $factory->getClient('http', $authKey);

$client->setSenderId('put a sender id here'); 
$client->setCountryId('put a contry id here');

$sms = new Sms('put the message here');
$sms->pushDestination('+391231231234');
$client->send($sms); 
```

To send a **bulk sms** add more destination using the *pushDestination* method. 

#### Generate an OTP

```php
use GuzzleHttp\Client;
use Adresser\Smsbot\Enviroment;
use Adresser\Smsbot\OtpGenerator;
use Adresser\Smsbot\OtpValidator;
use Adresser\Smsbot\RequestDispatcher;

// next updates will provide an easy-to-use factory 
$serverUri = Enviroment::getConfiguration('server_uri'); 
$httpClient = new Client(['base_uri' => $serverUri]); 
$requestDispatcher = new RequestDispatcher('put your auth key here', $httpClient); 

$generator = new OtpGenerator($requestDispatcher);
$validator = new OtpValidator($requestDispatcher); 

// set the right timezone before calling getExpiration method. 
$otp = $generator->generate('put OTP lifetime in seconds here'); 
echo $otp . " will exceed in " . $otp->getExpiration() . " \n"; 

if ($validator->validate($otp)) {
	echo "OTP successfully validated! \n"; 
}
```

