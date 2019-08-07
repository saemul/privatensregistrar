<?php
class Privatens{
    function message($code, $msg){
        return [
            "code"    => $code,
            "message" => $msg
        ];
    }

    function messageWithData($code, $msg, $data){
        return [
            "code"    => $code,
            "message" => $msg,
            "data"    => $data
        ];
    }

    function request($url, $method, $oauth2, $datas){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => http_build_query($datas),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$oauth2,
            "Content-Type: application/x-www-form-urlencoded",
            "X-Requested-With: XMLHttpRequest"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return json_decode($response);
        }
    }

    function authentication($url,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url."/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($data)
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);

        return $result;
    }
}

function privatens_getConfigArray(){
    $configarray = array(
        "apiurl" => array (
            "FriendlyName" => "API Url",
            "Type"         => "text", # Text Box
            "Size"         => "255", # Defines the Field Width
            "Description"  => "API Url",
            "Default"      => "",
            "Placeholder"  => "API Url"
        ),
        "clientid" => array (
            "FriendlyName" => "Client Id",
            "Type"         => "text", # Text Box
            "Size"         => "255", # Defines the Field Width
            "Description"  => "Client Id API Privatens",
            "Default"      => "",
            "Placeholder"  => "Client Id"
        ),
        "secretid" => array (
            "FriendlyName" => "Secret Id",
            "Type"         => "text", # Text Box
            "Size"         => "255", # Defines the Field Width
            "Description"  => "Secret Id API Privatens",
            "Default"      => "",
            "Placeholder"  => "Secret Id"
        )
    );
	
    return $configarray;
}

function privatens_GetNameservers($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain" => $params['sld'].".".$params['tld']
    ];
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);
        
        $request = $main->request($params['apiurl']."/rest/v2/domain/get/info","POST",$auth->access_token,$datas);
    
        if($request->code == 200){
            $ns = explode(',',$request->data->nameserver);
            $i = 1;
            foreach($ns as $nameserver){
                $values["ns".$i]=$nameserver;
                $i++;
            }

            return $values;
        }
        
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_SaveNameservers($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain"     => $params['sld'].".".$params['tld'], 
        "nameserver" => [$params['ns1'],$params['ns2'],$params['ns3'],$params['ns4']]
    ];
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/nameserver/update","PUT",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_RegisterDomain($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $data = [
        "domain"      => $params['sld'].".".$params['tld'],
        "period"      => $params['regperiod'],
        "nameserver"  => [$params['ns1'],$params['ns2'],$params['ns3'],$params['ns4']],
        "description" => "WHMCS Register Domain"
    ];

    $registrant = array(
        'company_name'     => $params['companyname'],
        'initial'          => substr($params['firstname'],0,1).substr($params['lastname'],0,1),
        'first_name'       => $params['firstname'],
        'last_name'        => $params['lastname'],
        'gender'           => 'M',
        'street'           => $params['address1'],
        'street2'          => $params['address2'],
        'number'           => 13,
        'city'             => $params['city'],
        'state'            => $params['state'],
        'zip_code'         => $params['postcode'],
        'country'          => $params['country'],
        'email'            => $params['email'],
        'telephone_number' => str_replace('.','',$params['fullphonenumber']),
        'locale'           => 'en_GB'
      );

    if(($data['nameserver'][0] == "")){
        unset($data['nameserver']);
    }else{
        foreach($data['nameserver'] as $key => $value){
            if (empty($value)) {
                unset($data['nameserver'][$key]);
             }
        }
    }

    $datas = array_merge($data,$registrant);
    
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/create","POST",$auth->access_token,$datas);

        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_TransferDomain($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $data = [
        "domain"      => $params['sld'].".".$params['tld'],
        "auth_code"   => $params['eppcode'],
        "period"      => $params['regperiod'],
        "nameserver"  => [$params['ns1'],$params['ns2'],$params['ns3'],$params['ns4']],
    ];

   $registrant = array(
        'company_name'     => $params['companyname'],
        'initial'          => substr($params['firstname'],0,1).substr($params['lastname'],0,1),
        'first_name'       => $params['firstname'],
        'last_name'        => $params['lastname'],
        'gender'           => 'M',
        'street'           => $params['address1'],
        'street2'          => $params['address2'],
        'number'           => 13,
        'city'             => $params['city'],
        'state'            => $params['state'],
        'zip_code'         => $params['postcode'],
        'country'          => $params['country'],
        'email'            => $params['email'],
        'telephone_number' => str_replace('.','',$params['fullphonenumber']),
        'locale'           => 'en_GB'
      );

    if(($data['nameserver'][0] == "")){
        unset($data['nameserver']);
    }else{
        foreach($data['nameserver'] as $key => $value){
            if (empty($value)) {
                unset($data['nameserver'][$key]);
             }
        }
    }

    $datas = array_merge($data,$registrant);

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/transfer","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_RenewDomain($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain"         => $params['sld'].".".$params['tld'],
        "period"         => $params['regperiod'],
    ];

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/renew","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        } 
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
        
}

function privatens_GetEPPCode($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain"      => $params['sld'].".".$params['tld'],
    ];

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/get/eppcode","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return array('eppcode' => $request->data);
        }       
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_GetRegistrarLock($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain" => $params['sld'].".".$params['tld']
    ];

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/get/info","POST",$auth->access_token,$datas);

        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            $status = $request->data->thief_protection == 1 ? 'locked':'unlocked';
            return $status;
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }

}

function privatens_SaveRegistrarLock($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "domain"         => $params['sld'].".".$params['tld'],
        "status"         => ($params['lockenabled'] == 'locked') ? 'lock':'unlock' 
    ];
    
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/domain/togel/lock","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return ['success' => true];
        } 
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

function privatens_RegisterNameserver($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "name" => $params['nameserver'],
        "ip"   => $params['ip'],
        "ip6"  => $params['ip6']
    ];
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/child/nameserver/create","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

function privatens_ModifyNameserver($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "nameserver_id" => $params['nameserver_id'],
        "ip"            => $params['ip'],
        "ip6"           => $params['ip6']
    ];
    
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);


        $request = $main->request($params['apiurl']."/rest/v2/child/nameserver/update","PUT",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

function privatens_DeleteNameserver($params) {
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    $datas = [
        "nameserver_id" => $params['nameserver_id']
    ];
    
    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/child/nameserver/delete","DELETE",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return $main->message($request->code,$request->message);
        }     
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

function privatens_GetContactDetails($params){
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope" => "",

    ];
    
    $datas = [
        "domain"         => $params['sld'].".".$params['tld']
    ];

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/registrant/detail","POST",$auth->access_token,$datas);
        
        if($request->code !== 200){
            return ["error" => $request->message];
        }else{
            return array(
                'Registrant' => array(
                    'First Name' => $request->data->contact_first_name,
                    'Last Name' => $request->data->contact_last_name,
                    'Company Name' => $request->data->contact_company_name,
                    'Email Address' => $request->data->contact_email,
                    'Address 1' => $request->data->contact_street,
                    'Address 2' => "",
                    'City' => $request->data->contact_city,
                    'State' => $request->data->contact_state,
                    'Postcode' => $request->data->contact_zip_code,
                    'Country' => $request->data->contact_country,
                    'Phone Number' => $request->data->contact_phone,
                    'Fax Number' => "",
                ),
            );
        }     
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
    
}

function privatens_SaveContactDetails($params){
    $oauth2 = [
        "grant_type"    => "client_credentials",
        "client_id"     => $params['clientid'],
        "client_secret" => $params['secretid'],
        "scope"         => "",

    ];
    
    $datas = [
        "domain" => $params['sld'].".".$params['tld']
    ];

    try {
        $main=new Privatens;
        $auth = $main->authentication($params['apiurl'],$oauth2);

        $request = $main->request($params['apiurl']."/rest/v2/registrant/detail","POST",$auth->access_token,$datas);
        
        $contactDetails = $params['contactdetails']['Registrant'];
        $contact_datas = [
            'contact_id'       => $request->data->client_contact_id,
            'telephone_number' => str_replace('.','',$contactDetails['Phone Number']),
            'street'           => $contactDetails['Address 1'],
            'number'           => 13,
            'zip_code'         => $contactDetails['Postcode'],
            'city'             => $contactDetails['City'],
            'state'            => $contactDetails['State'],
            'country'          => $contactDetails['Country'],
            'email'            => $contactDetails['Email Address'],
        ];        
        
        $reply = $main->request($params['apiurl']."/rest/v2/registrant/update","PUT",$auth->access_token,$contact_datas);

        if($reply->code !== 200){
            return ["error" => $request->message];
        }else{
            return [
                'success' => true,
            ];
        }
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}