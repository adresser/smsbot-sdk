<?php

namespace Adresser\Smsbot; 

use Adresser\Smsbot\Sms;
use Illuminate\Support\Collection;

/**
 * SmsClient contract impose all the must-have methods 
 * that smsclients implementation must provide to the 
 * user. 
 */
interface SmsClientContract 
{
    public function send(Sms $sms): void; 

    public function getSmsHistory(): Collection; 

    public function getQueuedSms(): Collection; 
}