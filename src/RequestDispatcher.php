<?php

namespace Adresser\Smsbot; 

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;

/**
 * The RequestDispatcher communicate with the smsbot api set
 * doing HTTP request. Having only one dispatcher used for all 
 * the API feature is quite convinient so we can avoid to 
 * re-implement the http call. 
 * 
 */
class RequestDispatcher 
{
    private string $authenticationKey; 

    private ClientInterface $httpClient; 

    public function __construct (string $authenticationKey, ClientInterface $httpClient) 
    {
        $this->authenticationKey = $authenticationKey; 
        $this->httpClient = $httpClient; 
    }

    /**
     * @param string $route
     * @param string $method
     * @param array $query
     * @param array $form
     * @return Response
     */
    public function doRequest(string $route, string $method, array $query = [], array $form = []): Response  
    {
        $mustHaveParameters = [
            'auth_key'  => $this->authenticationKey, 
            'route'     => $route
        ]; 

        $queryBag = array_merge($query, $mustHaveParameters); 
        $response = $this->httpClient->request($method, '', [
            'query' => $queryBag, 
            'form_params'  => $form
        ]);
        
        if (($statusCode = $response->getStatusCode()) != 200) 
            throw new Exception("Response failed with status code " . $statusCode, 1);
            
        if ($this->gotUnauthenticatedError($response)) 
            throw new Exception("Response failed, unauthenticated", 1);

        return $response; 
    }

    protected function gotUnauthenticatedError(Response $response): bool
    {
        return json_decode((string) $response->getBody(), true) == false; 
    }
}