<?php

namespace Adresser\Smsbot; 

use Exception;
use Illuminate\Support\Collection;

class Sms 
{
    const PLAIN_ENCODING = 'Plain';
    const UNICODE_ENCODING = 'Unicode';
    const PHONE_NUMBER_REGEX = "/^\+\d{11,15}$/";

    private static array $allowedEncodings = ['Plain', 'Unicode'];

    private Collection $destinations; 

    private string  $text;

    private string  $encoding; 

    private array   $databaseInfos = [
        "id"            => null,
        "send_through"  => null,
        "gateway_id"    => null, 
        "sender_id"     => null,
        "country_id"    => null,
        "response_id"   => null,
        "status"        => null,
        "device_id"     => null,
        "sim_id"        => null,
        "campaign_id"   => null,
        "created_at"    => null,
    ]; 

    public function __construct (string $text, string $encoding = 'Plain')
    {
        $this->text = $text; 
        
        if (!$this->isValidEncoding($encoding)) 
            throw new Exception("Invalid encoding", 1);
        
        $this->encoding = $encoding; 
        $this->destinations = new Collection; 
    }

    public function __toString (): string 
    {
        $mandatoryParameters = [
            'destinations'  => $this->destinations->toArray(), 
            'text'          => $this->text, 
            'encoding'      => $this->encoding
        ]; 

        return json_encode(array_merge($mandatoryParameters, $this->databaseInfos));
    }

    public function pushDestination (string $destination): void 
    {
        if (! $this->isValidPhoneNumber($destination)) {
            throw new Exception("Invalid phone number.", 1);
        }

        $this->destinations->add($destination); 
    }

    public function getDestinations (): Collection 
    {
        return $this->destinations; 
    }

    public function setEncoding (string $encoding): void 
    {
        if (!$this->isValidEncoding($encoding)) 
            throw new Exception("Invalid encoding", 1);
        
        $this->encoding = $encoding; 
    }

    public function getEncoding (): string 
    {
        return $this->encoding; 
    }

    public function setText(string $text): void 
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text; 
    }

    public function setDatabaseInfos (array $data): void
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->databaseInfos)) {
                $this->databaseInfos[$key] = $value;
            }
        }
    }

    public function getDatabaseInfos (): array
    {
        return $this->databaseInfos;
    }

    public function isBulk(): bool
    {
        return $this->destinations->count() > 1; 
    }

    private function isValidEncoding (string $encoding): bool
    {
        return in_array($encoding, self::$allowedEncodings);
    }

    private function isValidPhoneNumber(string $phoneNumber): bool
    {
        return preg_match(self::PHONE_NUMBER_REGEX, $phoneNumber);
    }
}