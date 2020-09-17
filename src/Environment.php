<?php

namespace Adresser\Smsbot; 

/**
 *  Retrieve configurations from the configuration file 'config.php'. 
 */
final class Environment
{
    public static function getConfiguration(string $key): string 
    {
        $configurations = include __DIR__ . '/../config.php'; 

        if (! array_key_exists($key, $configurations)) 
            throw new \Exception("Configuration key '$key' doesn't exists", 1);
        
        return $configurations[$key]; 
    }
}