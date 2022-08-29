<?php
if (!defined("WHMCS")) die("This file cannot be accessed directly");
use WHMCS\Database\Capsule  as DB;
include 'moid.php';

class MainDnsPrivatensX 
{   
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

    function checkStatus($params,$domain) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "domain" => $domain
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/check/status","POST",$auth->access_token,$datas);
            
            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function confirmChangeNs($params,$domain) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "domain" => $domain
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/confirm","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function createDNS($params,$domain) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "domain" => $domain,
            "ip" => '103.102.152.5',
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/create","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function addDNS($params,$data) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = $data;
        $datas['isroot'] = true;
        
        $name = $datas['name'];
        if (!isset($name) || $name == '') {
            $datas['name'] = $datas['domain'] . '.';
        }
        
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/add","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function deleteDNS($params,$domain,$line) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "line"=>$line,
            "domain"=> $domain,
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/delete","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function terminateDNS($params,$domain) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "domain" => $domain,
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/terminate","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }

    function listDNS($params,$domain) {
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $params['clientid'],
            "client_secret" => $params['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "domain" => $domain,
        ];
        try {
            $auth = $this->authentication($params['apiurl'],$oauth2);
            
            $request = $this->request($params['apiurl']."/rest/v2/dnsmanagerv2/list","POST",$auth->access_token,$datas);

            return $request;
            
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage(),
            );
        }
        
    }
}

function privatens_registrar_config() {
  $configarray = array(
    "name" => "PrivateNS Registrar",
    "description" => "This is a privatens registrar config function for an addon module",
    "version" => "1.0",
    "author" => "PrivateNS Registrar",
    "fields" => array( 
      "email_privatens" => array (
          "FriendlyName" => "Email",
          "Type"         => "text", # Text Box
          "Size"         => "255", # Defines the Field Width
          "Description"  => "Email Login Irsfa",
          "Default"      => "",
          "Placeholder"  => "namaemail@domain.com"
      ),
      "apiurl" => array (
          "FriendlyName" => "API Url",
          "Type"         => "text", # Text Box
          "Size"         => "255", # Defines the Field Width
          "Description"  => "API url",
          "Default"      => "",
          "Placeholder"  => "API url"
      ),
      "clientid" => array (
          "FriendlyName" => "Client Id",
          "Type"         => "text", # Text Box
          "Size"         => "255", # Defines the Field Width
          "Description"  => "Client Id API privatens",
          "Default"      => "",
          "Placeholder"  => "Client Id"
      ),
      "secretid" => array (
          "FriendlyName" => "Secret Id",
          "Type"         => "text", # Text Box
          "Size"         => "255", # Defines the Field Width
          "Description"  => "Secret Id API privatens",
          "Default"      => "",
          "Placeholder"  => "Secret Id"
      )
    ),
  );
  return $configarray;
}

function privatens_registrar_output($vars) {
  $m= new Moid;
  $m->display_admin($vars);
}

function privatens_registrar_clientarea($vars) {
    $page = $_GET['page'];
	//validate
	$domainID=(int)$_GET['id'];
	$domain = DB::table('tbldomains')
				->where('id',$domainID)
				->where('userid',$_SESSION['uid'])
				->value('domain');

	/* if(!$domain){
		$message = ['status' => 'failed', 'messages' => 'Access not permitted..!'];
	} */
	
    if ($page === 'dnsmanager') {
		
        $main      = new MainDnsPrivatensX;

        $modulelink = $vars['modulelink'];
        $version = $vars['version'];
        $session    = $_SESSION['uid'];
        $domainname = $domain;
        $datas      = array(
            'session'   =>$session,
            'domainname'=>$domainname,
            'vars'=>json_encode($vars),
        );
    
    
        if(isset($_GET['delete'])){ 
            $domainname=$domain;
            $ids=$_GET['ids'];
            $deleteDns = $main->deleteDNS($vars, $domainname, $ids);
            if($deleteDns->code == 200){
                $message = ['status' => 'success', 'messages' => $deleteDns->message];
            }else{
                $message = ['status' => 'failed', 'messages' => 'Failed delete dns record. Please contact your administrator!'];
            }
    
            $getDNS = $main->listDNS($vars,$domain);
            $dataDns = ['dns' => $getDNS->data, 'domainname' =>$domain, 'message' => $message];
            return array(
                'pagetitle'    => 'Privatens DNS Manager',
                'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
                'templatefile' => 'clienthome',
                'requirelogin' => true, # accepts true/false
                'forcessl'     => false, # accepts true/false
                'vars'         => $dataDns,
            );
        }
    
        if(isset($_GET['terminate'])){ 
            $domainname=$domain;
            $terminateDns = $main->terminateDNS($vars,$domainname);
            if($terminateDns->code == 200){
                $message = ['status' => 'success', 'messages' => $terminateDns->message];
            }else{
                $message = ['status' => 'failed', 'messages' => 'Failed delete dns record. Please contact your administrator!'];
            }
    
            $dataDns = ['domainname' =>$domain, 'message' => $message];
            return array(
                'pagetitle'    => 'Privatens DNS Manager',
                'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
                'templatefile' => 'confirm',
                'requirelogin' => true, # accepts true/false
                'forcessl'     => false, # accepts true/false
                'vars'         => $dataDns,
            );
        }
        
        if(isset($_GET['add'])){   
            $domainname=$domain;
            // $data=array(
            //     'domain'=>$_POST['domain'],
            //     'host'  =>$_POST['host'],
            //     'type'  =>$_POST['type'],
            //     'value' =>$_POST['value'],
            //     'ttl'   =>$_POST['ttl'],
            // );
            $data = $_POST;
			
            $addDNS = $main->addDNS($vars,$data);
            if($addDNS->code == 200){
                $message = ['status' => 'success', 'messages' => $addDNS->message];
            }else{
                $message = ['status' => 'failed', 'messages' => 'Failed add dns record. Please contact your administrator!'];
            }
    
            $getDNS = $main->listDNS($vars,$domain);
            $dataDns = ['dns' => $getDNS->data, 'domainname' =>$domain, 'message' => $message];
            return array(
                'pagetitle'    => 'Privatens DNS Manager',
                'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
                'templatefile' => 'clienthome',
                'requirelogin' => true, # accepts true/false
                'forcessl'     => false, # accepts true/false
                'vars'         => $dataDns,
            );
            
        }
    
        if(isset($_GET['confirm'])){
            $changeNs = $main->confirmChangeNs($vars,$domain);
            if($changeNs->code == 200){
                $createDNS = $main->createDNS($vars,$domain);
                if($createDNS->code == 200){
                    $message = ['status' => 'success', 'messages' => $createDNS->message];
                }else{
                    $message = ['status' => 'failed', 'messages' => 'Failed create dns record. Please contact your administrator!'];
                }
                
                sleep (10);
    
                $getDNS = $main->listDNS($vars,$domain);
                $allData = ['dns' => $getDNS->data, 'domainname' =>$domain];
                return array(
                    'pagetitle'    => 'Privatens DNS Manager',
                    'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
                    'templatefile' => 'clienthome',
                    'requirelogin' => true, # accepts true/false
                    'forcessl'     => false, # accepts true/false
                    'vars'         => $allData,
                );
    
            }
    
        }
        
        $check=$main->listDNS($vars,$domain);

        if(!boolval($check->data->data->zone)){
            return array(
                'pagetitle'    => 'Privatens DNS Manager',
                'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
                'templatefile' => 'confirm',
                'requirelogin' => true, # accepts true/false
                'forcessl'     => false, # accepts true/false
                'vars'         => $datas,
            );
        }
    
    
        $getDNS = $main->listDNS($vars,$domain);
        $allData = ['dns' => $getDNS->data, 'domainname' =>$domain];
        return array(
            'pagetitle'    => 'Privatens DNS Manager',
            'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Privatens DNS Manager'),
            'templatefile' => 'clienthome',
            'requirelogin' => true, # accepts true/false
            'forcessl'     => false, # accepts true/false
            'vars'         => $allData,
        );
    }elseif($page === 'dnssecmanager'){
		/*dnssecmanager*/
		include __DIR__.'/lib/dnssec/class/DnsSecClass.php';
		include __DIR__.'/lib/dnssec/helpers.php';
		$id     = $_SESSION['uid'];
        $domain = $_GET['domain'];
        $action = $_GET['action'];
        $page   = $_GET['page'];
		
		$basePath='lib/dnssec/';
		$baseURL='/index.php?m=privatens_registrar&page=dnssecmanager&domain='.$domain ;
		
		
		
		$dnsSec = new DnsSecClass($vars['apiurl'], $vars['clientid'] , $vars['secretid']);
		
		// validasi domain milik dia    
        $users = DB::table('tbldomains')
				->where('userid', $id)
				->where('domain', $domain)
				->first();
		//dd($users);die();
        $data = array();
		
			if($users){
				// API action
				if ($action == 'insert') {
					$data = array(
						'domain' => $domain, //'devtestlagi.my.id',
						'key_tag'=> $_POST[key_tag],
						'alg'=> $_POST[alg],
						'digest_type'=> $_POST[digest_type],
						'digest' => $_POST[digest],
						'pubKey' => $_POST[pubKey],
					);
					
					if (!in_array($_POST[digest_type], ["0","1","2","3","4"])) {
						return renderError($baseURL,$id, $domain, "Invalid digest type");
					}
					
					$response = $dnsSec->addDNS($data);
					
					if ($response->code == 200) {
						header('Location: '.$baseURL);
					} else {
						// var_dump($response);
						return renderError($baseURL,$id, $domain, $response->message);
					}
					die();
				}
				
				if ($action == 'delete') {
					$data = array(
						'domain' =>  $domain, //'devtestlagi.my.id',
						'key_tag'=> $_POST[key_tag],
						'alg'=> $_POST[alg],
						'digest_type'=> $_POST[digest_type],
						'digest' => $_POST[digest]
					);
					$response = $dnsSec->deleteDNS($data);
					
					if ($response->code == 200) {
						header('Location: ' . $baseURL);
					} else {
						// var_dump($response);
						return renderError($baseURL,$id, $domain, $response->message);
					}
					die();
				}
				
				
				if ($action == 'forminsert') {
					return array(
						'pagetitle' => 'Tambah DNSSEC ',
						'breadcrumb' => array($baseURL=>'insert dns'),
						'templatefile' => $basePath.'tpl/client_insert',
						'requirelogin' => true, # accepts true/false
						'forcessl' => true, # accepts true/false
						'vars' => array(
							'id' => $id,
							'domain' => $domain,
							'page' => 'insert', 
						)
					);
				}
				
				
				
				if (isset($domain)){
					$response = $dnsSec->listDNS($domain); // 'devtestlagi.my.id' 
				}
				
							
				if ($response->code == 200){
					$data = $response->data->data->dsData;
					if ( gettype($data) == "object") {
						$data = [(array)$data];
					} else {
						$data = array_map(function($obj){
							return (array) $obj;
						},$data);
					}
				} else {
					return renderError($baseURL,$id, $domain, $response->message);
				}
				
			/* print_r(array(
					'pagetitle' => 'DNSSEC Manager',
					'breadcrumb' => array($baseURL=>'list dns'),
					'templatefile' => $basePath.'tpl/client_list',
					'requirelogin' => true, # accepts true/false
					'forcessl' => true, # accepts true/false
					'vars' => array(
						'id'     => $id,
						'domain' => $domain,
						'data'   => $data,
						'page' => 'list', 
					)
				));exit(); */
				
				return array(
					'pagetitle' => 'DNSSEC Manager',
					'breadcrumb' => array($baseURL=>'list dns'),
					'templatefile' => $basePath.'tpl/client_list',
					'requirelogin' => true, # accepts true/false
					'forcessl' => true, # accepts true/false
					'vars' => array(
						'id'     => $id,
						'domain' => $domain,
						'data'   => $data,
						'page' => 'list', 
					)
				);
					
			} else {
				return array(
					'pagetitle' => 'Invalid Process',
					'breadcrumb' => array($baseURL=>'Error Message'),
					'templatefile' => $basePath.'tpl/client_error',
					'requirelogin' => true, # accepts true/false
					'forcessl' => true, # accepts true/false
					'vars' => array(
						'id'     => $id,
						'domain' => $domain,
						'error'  => 'Domain tidak ditemukan di user'
					)
				);
			}
			
	}else {
        $m= new Moid;
        return $m->display_client($vars);
    }
}


/* function TLDSync(){
	
	
	
} */




function privatens_registrar_activate() {
  # Create Custom DB Table
  $create_box    = "CREATE TABLE mod_box (id varchar(100), idwhmcs varchar(100), comid varchar(100),type varchar(20),file text, meta text)";
  $create_idcard = "CREATE TABLE privatensdocument (idwhmcs varchar(100),domain varchar(200),syarat text,file_meta text)";
  $query = mysql_query($create_box);
  $query = mysql_query($create_idcard);
  # Return Result
  return array('status'=>'success','description'=>'Success Building module.. ');
}

function privatens_registrar_deactivate() {
  # Create Custom DB Table
  $create_box    = "DROP TABLE mod_box";
  $create_idcard = "DROP TABLE privatensdocument";
  $query = mysql_query($create_box);
  $query = mysql_query($create_idcard);
  # Return Result
  return array('status'=>'success','description'=>'All Your "privatensdocument" data has been remove ');
}


