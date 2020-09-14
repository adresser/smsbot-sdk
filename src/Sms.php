<?php

namespace Adresser\Smsbot; 

use Illuminate\Support\Collection;

class Sms 
{
    const ENCODINGS = ['Plain', 'Unicode']; 
    
    // const PHONE_NUMBER_REGEX = "/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/";
    const PHONE_NUMBER_REGEX = "/^[0-9]{10}+$/";

    private Collection $destinations; 

    private string  $text;

    private string  $encoding; 

    private array   $databaseInformations = [
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
            throw new \Exception("Invalid encoding", 1);
        
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

        return json_encode(array_merge($mandatoryParameters, $this->databaseInformations)); 
    }

    public function pushDestination (string $destination): void 
    {
        if (! $this->isValidPhoneNumber($destination)) {
            throw new \Exception("Invalid phone number.", 1);
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
            throw new \Exception("Invalid encoding", 1);
        
        $this->encoding = $encoding; 
    }

    public function getEncoding (): string 
    {
        return $this->encoding; 
    }

    public function setText(string $text): void 
    {
        $this->text = $test; 
    }

    public function getText(): string
    {
        return $this->text; 
    }

    public function setDatabaseInformations (array $data): void 
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->databaseInformations)) {
                $this->databaseInformations[$key] = $value; 
            }
        }
    }

    public function getDatabaseInformations (): array 
    {
        return $this->databaseInformations; 
    }

    public function isBulk()
    {
        return $this->destinations->count() > 1; 
    }

    private function isValidEncoding (string $encoding): bool
    {
        return in_array($encoding, self::ENCODINGS); 
    }

    private function isValidPhoneNumber(string $phoneNumber): bool
    {
        return true; 
        // return preg_match(self::PHONE_NUMBER_REGEX, $phoneNumber); 
    }
}