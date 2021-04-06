<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

class DnsSecClass
{
    // private  $api_url = 'https://api.irsfa.id/';
    // private  $client_id = 'ecc1d21e-f873-4a86-ac37-982adc0fc239';
    // private  $client_secret = 'DvMjDv0EaYbBZbfKgJma4u7EN6DL51Dzjy9J46Lh';
    
    
    
    function __construct($api_url, $client_id, $client_secret) {
        $this->api_url = $api_url;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }
    
    
    /**
	 * @return associative array token 
	 */
    private function auth() {
        $data = array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            scop
        );
        
        try {
            $response = $this->curlPost($this->api_url . '/oauth/token', $data, null);
            
            $response = json_decode($response);
            if ($response->error) {
                // var_dump($response);
                // die();
                
                return [
                    'status' => false,
                    'message' => json_encode($response), 
                ];
                
            } else {
                return [
                    'status' => true,
                    'data' => $response->access_token,
                ];
            }
        } catch (Exception $e) {
            var_dump($e);
            die();
        }
        
    }
    
    
    /**
	 * @param string $url 
	 * @param array $data 
	 * @return any  
	 */
    
    private function curlPost($url, $data, $token){
        
        $headers = array(
            "Content-Type: multipart/form-data;"
        );
        
        
        $payload = $data;
        $ch = curl_init($url);
        
        if (isset($token)){
            $headers = array(
                "Content-Type: application/json",
                "X-Requested-With: XMLHttpRequest",
                "Authorization: Bearer " . $token,
            );
            
            $payload = json_encode($data);
        }


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    
    /**
	 * @param string $domain 
	 * @return object  
	 */
    public function listDNS($domain){
        $token = $this->auth();
        if (!$token['status']){
            return (object)[
                'code' => 500,
                'message' => $token['message']
            ];
        }
        $token = $token['data'];
        $response = $this->curlPost($this->api_url . '/rest/v2/dnssec/list', ["domain"=>$domain] , $token);
        return json_decode($response);
    }
    
    
    /**
	 * @param associative_array $data 
	 * @return object  
	 */
    public function addDNS($data){
        $token = $this->auth();
        if (!$token['status']){
            return (object)[
                'code' => 500,
                'message' => $token['message']
            ];
        }
        $token = $token['data'];
        $response = $this->curlPost($this->api_url . '/rest/v2/dnssec/add', $data , $token);
        return json_decode($response);
    }
    
    /**
	 * @param associative_array $data 
	 * @return object  
	 */
    public function deleteDNS($data){
        $token = $this->auth();
        if (!$token['status']){
            return (object)[
                'code' => 500,
                'message' => $token['message']
            ];
        }
        $token = $token['data'];
        $response = $this->curlPost($this->api_url . '/rest/v2/dnssec/remove', $data , $token);
        return json_decode($response);
    }
    
}