<?php
require_once __DIR__ . '/../../../init.php';
require 'email/src/Exception.php';
require 'email/src//PHPMailer.php';
require 'email/src//SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

global $CONFIG;

class Moid
{
    private static  $dir = __DIR__ .'/files/';
    protected $dir_read ;
    function __construct()
    {
        $this->dir_read = 'https://'.$_SERVER['SERVER_NAME'].'/modules/addons/privatens_registrar/files/';
    }

    private function _authentication($url,$data){
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

    private function _request($url, $method, $oauth2, $datas){
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
            "X-Requested-With: XMLHttpRequest",
            "content-type: multipart/form-data"
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


    private function _syarat(){
        $var = [
				'ac.id'     =>[
					'ktp','sk_rekor','sk_pendirian','surat_kuasa',
				],
				'biz.id'    =>[
					'ktp'
				],
				'co.id'     =>[
					'ktp','siup'
				],
				'my.id'     =>[
					'ktp'
				],
				'net.id'    =>[
					'ktp','surat_izin_prinsip'
				],
				'or.id'     =>[
					'ktp','akta_notaris'
				],
				'ponpes.id' =>[
					'ktp','sk_pimpinan'
				],
				'sch.id'    =>[
					'ktp','sk_kepsek','sk_pendirian'
				],
				'web.id'    =>[
					'ktp',
				],
				'id' =>[
					'ktp'
				],
				'br'=>[
				    'brazil_company_bussines_registration_licence'
				],
				'cn'=>[
				    'china_company_bussines_registration_licence',
				    'china_national_identity_card',
				],
				'de'=>[
				    'german_company_bussines_registration_licence'
				],
				'fr'=>[
				    'france_company_bussines_registration_licence'
				],
				'hu'=>[
				    'hungary_company_bussines_registration_licence'
				],
				'is'=>[
				    'iceland_company_bussines_registration_licence'
				],
				'jp'=>[
				    'japan_company_bussines_registration_licence'
				],
				'kr'=>[
				    'korea_company_bussines_registration_licence'
				],
				'lt'=>[
				    'lithuania_company_bussines_registration_licence'
				],
				'lu'=>[
				    'lu_company_bussines_registration_licence'
				],
				'no'=>[
				    'norway_company_bussines_registration_licence'
				],
				'md'=>[
				    'moldova_company_bussines_registration_licence'
				],
				'mx'=>[
				    'mexico_company_bussines_registration_licence'
				],
				'nu'=>[
				    'sweden_company_bussines_registration_licence'
				],
				'ph'=>[
				    'philippines_company_bussines_registration_licence'
				],
				'pl'=>[
				    'poland_company_bussines_registration_licence'
				],
				'pt'=>[
				    'portugal_company_bussines_registration_licence'
				],
				'se'=>[
				    'sweden_company_bussines_registration_licence'
				],
				'sg'=>[
				    'singapore_company_bussines_registration_licence'
				],
				'za'=>[
				    'south_african_company_bussines_registration_licence'
				],
			];
		return $var;
    }
    
    private function _tld($domain=''){
			$ex       =explode('.',$domain);
			$count    =count($ex);
			$path_tld =array();
			for ($i=1; $i < $count ; $i++) {
				$path_tld[]=$ex[$i];
			}			
			return $tld=implode('.', $path_tld);
	}
   
    public function display_admin($vars){
		if($_GET['page'] != 'syncTLD'){
			include 'views/head.php';
		}
        $page  = $_GET['page'];
        if($page=='all'){
            $this->_document_client($vars);
        }elseif($page=='approval')
        {
            $this->_table_approval($vars);
        }elseif($page=='document_client'){
            $this->_document_client_admin($vars);
        }
        elseif($page=='test'){
            $this->_inject_null_image();
        }elseif($page == 'syncTLD' ){
			
			
			return $this->syncTLD($vars);
			
		}else{
            $this->_inject_null_image();
            $this->_table_approval($vars);
        }
    }
	
	
	private function syncTLD($vars){
		header('Content-Type: application/json');
		$alert=true;
		$errorMsg='';
		$alertError='';
		$oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $vars['clientid'],
            "client_secret" => $vars['secretid'],
            "scope" => "",
        ];
		
		$params=[
					'oauth_client_id' 	=> $vars['clientid'],
					'kurs_id'			=> 1,
					'product_type_id'	=> 1,
					'period'			=> 1,
					'character'			=> 0
			];
		
		
		$auth = $this->_authentication($vars['apiurl'], $oauth2);
		$getPriceTLD=$this->_request($vars['apiurl']."/rest/v2/domain/list/pricing/reseller","POST",$auth->access_token,$params);
		if($getPriceTLD->code == 201){
			$command = 'CreateOrUpdateTLD';
			foreach($getPriceTLD->data as $tld){
				$postData=[
							'extension'		=> $tld->product_name,
							'currency_code' => 'IDR',
							'id_protection' => true,
							'dns_management' => true,
							'epp_required'	=> true,
							'email_forwarding' => true,
							'register' 		=> [1 => $tld->price_register],
							'renew' 		=> [1 => $tld->price_renew],
							'transfer'		=> [1 => $tld->price_transfer],
							'responsetype' => 'json'
						];
				$whmcs = localAPI($command, $postData);
				if($whmcs == 'error'){
					$alertError=$whmcs['message'];
					break; 
				}
			}
			
			$errorMsg=!empty($alertError)?$alertError:'Successfully Sync TLD Domain prices';
			$alert=!empty($alertError)?true:false;
			
		}else{
			$errorMsg=$getPriceTLD->message;
		}
		
		$respone=[
					'alert'		=> $alert,
					'errorMsg'	=> $errorMsg
				];
				
		echo json_encode($respone);
		exit();
	}
	
	
	
	
    
    private function _inject_null_image(){
       
         $sql = <<<SQL
       SELECT m.*,d.userid,u.firstname,u.email  FROM privatensdocument m 
                                        LEFT JOIN tbldomains d on d.domain=m.domain 
                                        LEFT JOIN tblclients u on u.id=d.userid
                                        WHERE syarat LIKE '%:"0"%' or syarat LIKE '%:0%'
SQL;

        $data_approval = mysql_query($sql);
        if($data_approval!=null){
            foreach($data_approval as $row){
               $syarat = $this->_recover_file($row);
            //   print_r($syarat);
            }
        }
    }
    
    function _recover_file($row){
        $domain = $row['domain'];
        $obj = json_decode($row['syarat'],true);
        $keys = array_keys($obj);
        foreach($keys as $key){
            $file = $obj[$key]['file'];
            if($file==null){
                // Jika file satu domain kosong maka 
                $var =[
                    'file_type'=>$key,
                    'domain' => $domain,
                    'idwhmcs'=>$this->_get_userid($domain),
                ];
               return $this->_do_recover($var);
            }
        }
    }
    
    function _do_recover($var){
        // Update file jia dia memiliki set untuk semua dan atau dia memiliki record lebih dari 1
        $idwhmcs= $var['idwhmcs'];
        $sql = "SELECT * from mod_box where idwhmcs ='$idwhmcs'";
        $query = mysql_query($sql);
        $records = mysql_fetch_object($query);
        $file_type = ($records->type=='image/jpeg') ? 'ktp':  $records->type;
        $data=array(
           $file_type=>array(
                    'file'=>$records->file,
                    'status'=>0,
                    'domain'=>$var['domain'],
            ),
        );
        $updater=json_encode($data);
        $domain = $var['domain'];
        $update = "update privatensdocument set syarat = '$updater' where domain ='$domain'";
        return mysql_query($update);
    }
   
    function sendEmail($domain=''){
 
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'privatens.id';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'YOUR USERNAME';                 // SMTP username
            $mail->Password = 'YOUR PASSWORD';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('notif@privatens.id', 'Document Manager');
            $mail->addAddress('billing@privatens.id', 'Billing');     // Add a recipient
           
        
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Notifikasi Upload Document -'.$domain;
            $body ='';
            $body .='Pemilik domain '.$domain.'Telah melakukan set dokumen silahkan untuk segera dicek <br>';
            
            $mail->Body    = $body;
            $mail->AltBody = 'Notifikasi Email Upload Document';
        
            $mail->send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
        
        
    }
	
    private function _document_client($vars){
        
        if(isset($_POST['user'])){
            $user =$_POST['user'];
            $add_search = "Where firstname like  '%$user%' or email like '%$user%'";
        }else{
            $add_search = '';
        }
        $this->validate_input($user);
        
        $sql ="SELECT count(b.id) as jumlah, t.id, firstname, email, companyname, phonenumber FROM tblclients t inner join mod_box  b on t.id=b.idwhmcs ".$add_search." GROUP BY t.id";
        
      
        $db_data = mysql_query($sql);
        include 'views/page_admin.php';

    }
    
    private function _table_approval(){
        $sql = <<<SQL
       SELECT m.*,d.userid,u.firstname,u.email  FROM privatensdocument m 
                                        LEFT JOIN tbldomains d on d.domain=m.domain 
                                        LEFT JOIN tblclients u on u.id=d.userid
                                        WHERE syarat LIKE '%:"0"%' or syarat LIKE '%:0%'
SQL;

        $data_approval = mysql_query($sql);
		//print_r(mysql_num_rows($data_approval));
        if(mysql_num_rows($data_approval)==0){
            echo "<br> - No Data Need Approval";
        }else{
            $table = array();
            while($data = mysql_fetch_object($data_approval)){
				/* echo '<pre>';
				print_r($data);
				echo '</pre>'; */
                if($data->syarat!=null){
                    $_syarat = json_decode($data->syarat,true);
                   
                    $keys = array_keys($_syarat);
                  
                 
                    $count = null;
                    foreach($keys as $key){
                        if($_syarat[$key]['status']==0){
                            $count[]=1;
                        }
                        else{
                            $count[]=0;
                        }
                    }
                    $total_waiting = array_sum($count);
                }
                else{
                    $total_waiting  = 0;
                }
                
                $table[]=(object)[
                    'file'       =>$total_waiting,
                    'client_name'=>$data->firstname, 
                    'client_email'=>$data->email, 
                    'domain'     =>$data->domain,
                ];
            }
          
            include 'views/page_approval.php';  
        }
         
    }
    
    function display_client($vars){
        
        $page = @$_GET['page']; 
        $do   = @$_GET['upload'];
        $uid =  $_SESSION['uid'];
        
        if($page =='null'){
			
            return  $this->_client_home($vars);     
        }
        elseif($page=='upload'){
            return $this->_upload_gambar($vars);
        }
        elseif($page=='remove'){
            $this->_remove_file($_GET['file'],$uid);
            return $this->_client_home($vars);
        }
        elseif($page=='requirement'){
            return $this->_requirement_page($var);
        }
        elseif($page=='set_doc'){
           $msg = $this->_set_document($vars,$uid);
           return  $this->_client_home($vars,$msg);
        }
        
        else{
			
            return  $this->_client_home($vars);     
        }
    }
    
    private function _document_client_admin($vars){
        
        $userid = $_GET ['userid'];
        
        $query = mysql_query("SELECT * FROM mod_box where idwhmcs = '$userid'");
        
        include 'views/document_client.php';
        
    }
    private function _requirement_page($vars){
         return array(
            'pagetitle'    => 'Domain document',
            'breadcrumb'   => array('index.php?m=privatens_registrar&page=requirement'=>'Domain Document'),
            'templatefile' => 'tpl/domain_document',
            'requirelogin' => true, # accepts true/false
            'forcessl'     => false, # accepts true/false
            'vars' => array(
                'uid'      =>$_SESSION['uid'],
                'domains'  =>$this->_myDomain($_SESSION['uid']),
                'document' =>$this->_list_file($_SESSION['uid']),
                'dir'      =>$this->dir_read,
                'table'    =>$this->_document_data($this->_myDomain($_SESSION['uid']))
            ),
        );
    }
    private function _document_data($client_domains){
        
        
        $_document = null;
        foreach($client_domains as $domain){
            
            $query = mysql_query("select * from privatensdocument where domain='$domain'");
            if(mysql_num_rows($query)!=0){
              
                $detail = mysql_fetch_object($query);
               
                $_syarat = json_decode($detail->syarat);
              
                
                //  Jumlah dokumen 
                $document_count = count($_syarat);
                
                /**
                 * JIka dalam 1 kumpulan sayarat terdapat 1 dokumen yang pending/ ditolak
                 * maka domain warning dan tidak dapat diteruskan proccessnya / bisa di suspend
                 */
                $warning = array();
                foreach($_syarat as $row){
                    if($row->status==0 or $row->status==2){
                        $warning[] = 1;
                    }
                }
                $total_warning =array_sum($warning);
                
                $status = ($total_warning<1) ? 'Ok' : 'Warning';
                
                $_document []=(object)[
                    'domain' =>$domain, 
                    'count'  => $document_count, 
                    'status' => $status,
                ];
                
            }
        }
      
        if($_document==null){
                $_document []= (object)[
                    'domain' =>'', 
                    'count'  =>'', 
                    'status' =>'',
                ];
            
        }
        return $_document;
        
    }
    private function _client_home($vars,$msg=''){
         return array(
            'pagetitle'    => 'Domain document',
            'breadcrumb'   => array('index.php?m=privatens_registrar'=>'Domain document'),
            'templatefile' => 'tpl/home',
            'requirelogin' => true, # accepts true/false
            'forcessl'     => false, # accepts true/false
            'vars' => array(
                'msg' =>$msg,
                'uid'      =>$_SESSION['uid'],
                'domains'  =>$this->_myDomain($_SESSION['uid']),
                'document' =>$this->_list_file($_SESSION['uid']),
                'dir'      =>$this->dir_read,
            ),
        );
    }
    private function _myDomain($idwhmcs){
        
       
        $syarat = $this->_syarat();
        $tlds = array_keys($syarat);
        $add_query =null;
        foreach($tlds as $tld){
            $add_query .=" or RIGHT(domain,".strlen($tld).")='$tld'";
            
        }
        $query = mysql_query("SELECT * FROM tbldomains WHERE userid='$idwhmcs' and (RIGHT(domain,2)='id' ".$add_query.")");
        $data = null;
        while($row = mysql_fetch_object($query)){
            $data[]=$row->domain;
        }
        return (object)$data ;
    }
    private function _remove_file($file,$uid){
        $querycheck = mysql_query("SELECT id from mod_box where file = '$file' AND idwhmcs = '$uid'");
        if (mysql_num_rows($querycheck)>0)
        {
        $query = mysql_query("delete from mod_box where file = '$file' AND idwhmcs = '$uid'");
        unlink(self::$dir.$file);
        $fopen = fopen($dir.'remove.txt',"w+");
        fwrite($fopen,"delete form mod_box where file = $file");
        }
    }
    private function _list_file($uid){
        if($uid==null){
            header("location:https://".$_SERVER["SERVER_NAME"]."");
            die;
        }
        $query = mysql_query("SELECT * FROM mod_box where idwhmcs = $uid");
        $data = null;
        while($row = mysql_fetch_object($query)){
            $data []= $row;
        }
        return (object)$data;
    }
    private function _upload_gambar($vars){
        $rename = uniqid().$this->_rename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], self::$dir.$rename);
        $fopen = fopen($dir.'t.txt',"w+");
        fwrite($fopen,$this->_save_image($_FILES['file'],$rename));
    }
    private function _rename($file){
        
        $name = explode(".", $file);
        $ext = $name[count($name)-1];
        return uniqid().".".$ext;
        
    }
    private function _save_image($file,$rename,$vars){
        $data = [
            'id'=>uniqid(),
            'idwhmcs' => $_SESSION['uid'],
            'comid'   => md5($vars['email_privatens'].$this->_get_user($_SESSION['uid'])->email),
            'type'    => $file['type'],
            'file'    => $rename,
            'meta'    => $this->_read_metadata(self::$dir.$rename),
        ];
        $field = implode(",",array_keys($data));
        $values = array_values($data);
        $q= null;
        foreach ($values as $val){
            $q.="'".$val."',";
        }
        $query = mysql_query("INSERT into mod_box ($field) values (".substr($q,0,strlen($q)-1).")");
        return  ("INSERT into mod_box ($field) values ($q)");
    }
    private function _read_metadata($file){
        $exif = exif_read_data($file, 0, true);
        return json_encode($exif);
    }
    private function _get_user($uid){
       $query = mysql_query("SELECT * From tblclients where id = $uid");
       return mysql_fetch_object($query);
    }
    
    private function _set_document($vars,$uid){
        
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => $vars['clientid'],
            "client_secret" => $vars['secretid'],
            "scope" => "",
    
        ];
        $datas = [
            "document_id"          => 1,
            "document_name"        => "Portal Upload",
            "document_description" => 'Portal Upload',
            "file"                 => "https://".$_SERVER['SERVER_NAME']."/modules/addons/privatens_registrar/files/".$_POST['file'],
            "domain"               => $_POST['domain']
        ];
        $this->validate_input($_POST['domain']);

        $auth = $this->_authentication($vars['apiurl'], $oauth2);

        $set_all = ($_POST['set_all']=='on') ? 1 : 0;
      
      
        $data = [
            'idwhmcs' => $uid,
            'domain'    => $_POST['domain'],
            'syarat'    => $this->_syarat_domain($_POST['file'],$_POST['domain'],$_POST['type']),
            'file_meta' => $this->_meta_document($_POST['file'],$_POST['domain'],$_POST['type']),
        ];
       
       $this->validate_input($_POST['domain']);
        $domain = $data['domain'];
       
        $check_record = mysql_query("SELECT domain from privatensdocument where domain='$domain' AND idwhmcs = '$uid'");
       
        
        if($check_record->num_rows==0){
             $this->_insert_privatensdocument($data);
             $this->_request($vars['apiurl']."/rest/v2/domain/upload/terms/portal","POST",$auth->access_token,$datas);
        }else{
             $this->_update_privatensdocument($data);
             $this->_request($vars['apiurl']."/rest/v2/domain/upload/terms/portal","POST",$auth->access_token,$datas);
        }
        
       
        if($set_all==1)
        {
          
            $file          = $_POST['file'];
           
            $idwhmcs       = $this->_get_userid($domain);
            
            
            $update_setall = mysql_query("update mod_box set set_all='$set_all', type = '".$_POST['type']."' where file = '$file'");
            $update_setall = mysql_query("update mod_box set set_all='0' where file != '$file' and  type = '".$_POST['type']."' and idwhmcs ='$idwhmcs'");
            
            // Update all value document for some type
            $update = $this->_update_all_domain_syarat($_POST['domain'],$_POST['type'],$_POST['file']);
          
        }
      
        /*$this->sendEmail($domain);*/
        return "Success set your document";
    }
    private function _get_userid($domain){
        $query = mysql_query("SELECT * from tbldomains where domain='$domain'");
        if(mysql_num_rows($query)!=0){
            $data = mysql_fetch_object($query);
            return $data->userid;
        }
    }
    private function _update_all_domain_syarat($domain,$type,$file){
        /**
         * this function will help to chage all documen/ syarat field value 
         * identified with type of document 
        */
        $idwhmcs = $this->_get_userid($domain);
        $this->validate_input($idwhmcs);
        
        $query = mysql_query("SELECT * from privatensdocument where domain = '$domain'");
     
        if(mysql_num_rows($query)!=0){
            // update all documen form 1 user and many domain
            $record        = mysql_fetch_object($query);
          
            $syarat        = json_decode($record->syarat,true);
            $syarat[$type] = ["file"=>$file,"status"=>0,"ket"=>'Replace by user doing set all'];
            
            $field_syarat  = json_encode($syarat);
            $query         = mysql_query("update privatensdocument set syarat = '$field_syarat' where domain='$domain'");
            /** 
             * So if the user have many domain but never set idcard , it will be automatic insert to the privatensdocument table from tbldomains tble with default value of syarat and status = 0 mean to pending
            */
            return $this->_insert_not_recorded_domain($idwhmcs,$type,$file);
        }
    }
    private function _insert_not_recorded_domain($idwhmcs,$type,$file){
        $syarat = $this->_syarat();
        $tlds   = array_keys($syarat);
        $add_query= null;
        foreach($tlds as $tld){
            $add_query .=" or RIGHT(domain,".strlen($tld).")='$tld'";
            
        }
        $query =mysql_query("SELECT domain from tbldomains where userid = '$idwhmcs' and  (RIGHT(domain,2)='id' ".$add_query.")");
       
       
        if(mysql_num_rows($query)!=0){
            $insert=array();
            while($row = mysql_fetch_object($query)){
                   $data = [
                        'domain'    => $row->domain,
                        'syarat'    => $this->_syarat_domain($file,$row->domain,$type),
                        'file_meta' => $this->_meta_document($file,$row->domain,$type),
                   ];
                    
                    // Jika ada di table domain dan  ada di table privatensdocument 
                   $check_avail  = mysql_query("SELECT domain from privatensdocument where domain ='$row->domain'");
                   if(mysql_num_rows($check_avail)!=0){
                       $this->_update_privatensdocument($data);
                   } else{
                       $insert [] = $this->_insert_privatensdocument($data);
                   }
                   
            }
           
            return $insert;
        }
    }
    private function _update_privatensdocument($data){
        $fields = array_keys($data);
        $values = array_values($data);
        $q = null;
        for ($i=0; $i <count($fields) ; $i++) { 
            $q .= $fields[$i]."='".$values[$i]."',";
        }
        $sql = "UPDATE privatensdocument set ".substr($q,0,strlen($q)-1)." where domain = '".$data['domain']."'";
        mysql_query($sql);

    }
    private function _insert_privatensdocument($data){
        $fields = implode(",",array_keys($data));
        $values = array_values($data);
        $q = null ;
        foreach($values as $val){
            $q .= "'".$val."',";
        }
        $q_values = substr($q,0,strlen($q)-1);
        $sql = "INSERT into privatensdocument ($fields) values ($q_values)";
        $insert = mysql_query($sql);
    }
    private function _meta_document($file,$domain,$type){
       
        $query = mysql_query("SELECT * From mod_box where file ='$file'");
        $rec_meta = mysql_fetch_object($query);
        
        // define new meta 
        $meta =[
            $type => [
                'file'     =>$file,
                'meta_data'=>$rec_meta,
                'set_by'   => $_SESSION['uid'],
                'time' =>time(),
            ], 
        ];
        // check meta in mod_id 
       
        $chek_doc= mysql_query("SELECT * from privatensdocument where domain='$domain'");
        if(mysql_num_rows($chek_doc)==0){
            $record = null ;
        }else{
            $record = mysql_fetch_object($chek_doc);
        }
        
        if($record ==null){
            //  If null create new meta 
            return json_encode($meta);
        }else{
            //  if there is already have meta data
            $record_meta = json_decode($record->file_meta,true); #define as array you need the key
            $keys = array_keys($record_meta);
           
            if(in_array($type,$keys )){
                // if meta already have just re-set the meta key
                $record_meta[$type]=$meta;
                return json_encode($record_meta);
            }else{
                // if doesn have any key 
                $merge = array_merge($record_meta ,$meta);
                return json_encode($merge);
            }
            
        }
    }
    private function _syarat_domain($file,$domain,$type){
        
        
        // define new record syarat 
        $syarat = [
                $type => [
                    'file'=>$file,
                    'status'=>0, #0 mean to waiting approve
                ],
        ];
        //  First read the privatensdocument and check is domain have any data before...
       
        $chek_doc= mysql_query("SELECT * from privatensdocument where domain='$domain'");
        if(mysql_num_rows($chek_doc)==0){
            $record = null ;
        }else{
            $record = mysql_fetch_object($chek_doc);
        }
        
        if($record == null) {
            // if the record was empty you can make json file by keys uploaded 
            return json_encode($syarat);
        }else{
            // if doest empty you must update the array from syarat field
            $rec_syarat = json_decode($record->syarat,true); #load as array you need array_keys
            $key = array_keys($rec_syarat);
            if(in_array($type,$key)){
                // if syarat have key just replace it
                $rec_syarat[$type] = $syarat[$type];
                return json_encode($rec_syarat);
            }else{
                //  if syarat record no have any key just merge 
                $merge = array_merge($rec_syarat,$syarat);
                return json_encode($merge);
            }
        }
    }
    private function _detail_document($file){
        $query = mysql_query("SELECT * from mod_box where file='$file'");
        return $data = mysql_fetch_object($query);
    }
    
    private function validate_input($param){
        if (strpos($param," ") > 0) {
      	    echo "eitsss,,,";
            die();
        }
    }
    
    // get preview domain document 
    public function domain_document(){
        $domain  = $_POST['domain'];
        $csrf  = $_POST['token'];
        if ($csrf != $_SESSION['csrftoken'] || $csrf == '')
        {
            die('ooops');
        }
        $this->validate_input($domain);
 
       $sql = <<<SQL
       SELECT m.*,d.userid,u.firstname,u.email  FROM privatensdocument m 
                                        LEFT JOIN tbldomains d on d.domain=m.domain 
                                        LEFT JOIN tblclients u on u.id=d.userid
                                        WHERE syarat LIKE '%:"0"%' or syarat LIKE '%:0%' and d.domain = '$domain'
SQL;
       
       
    //   echo $sql;
        $query = mysql_query($sql);
        while($row = mysql_fetch_object($query)){
            
           $docs=json_decode($row->syarat,true);
        }
       
        $keys = array_keys($docs);
        foreach($keys as $key){
            if($docs[$key]['status']==0){
                $doc[0][$key] =(object)$docs[$key];
            }    
        }
        if($doc == null) {
               echo "No Document To Display ";
        }else{
             include 'views/list_image.php';
        }
       
    }
    
    function proccess_doc(){
        $csrf  = $_POST['token'];
        if ($csrf != $_SESSION['csrftoken'] || $csrf == '')
        {
            die('ooops');
        }
        /*
        $queryuser  = mysql_query("SELECT id from tbladmins where id = '$id'");
        $datauser   = mysql_num_rows($queryuser);
        
        if ($datauser == 0)
        {
            die('oops');
        }*/
        
        $post= [
            'domain'=> $_POST['domain'],
            'ket'   => $_POST['ket'],
            'status'=> $_POST['status'], # approve =1 reject = 2
            'type'  => $_POST['key'] # type syarat yang diajukan
        ];
        
        $this->validate_input($_POST['domain']);
       
        $last_data = mysql_query("SELECT syarat from privatensdocument where domain = '". $post['domain']."'");
        
        $record = mysql_fetch_object($last_data);
        
        $syarat = json_decode($record->syarat,true);
        
        $update_status     = $syarat[$post['type']]['status'] = $post['status'];
        $update_keterangan = $syarat[$post['type']]['ket'] = $post['ket'];
       
        $_syarat = json_encode($syarat);
        $update_sql = mysql_query("update privatensdocument set syarat = '$_syarat' where domain = '". $post['domain']."'");
        $reply = [
            'code'=> '1000',
            'msg' => 'Document status proccessed'
        ];
        echo json_encode($reply);
    }
    
    public function lookup_tld($doc=''){
        $domain = $_GET['domain'];
        $csrf  = $_GET['token'];
        $id = $_GET['id'];
        if ($csrf != $_SESSION['csrftoken'] || $csrf == '')
        {
            die('ooops');
        }
        
        $queryuser  = mysql_query("SELECT id from tbldomains where userid = '$id' AND domain = '$domain'");
        $datauser   = mysql_num_rows($queryuser);
        
        if ($datauser == 0)
        {
            die('oops');
        }
        
        $this->validate_input($domain);
        $syarat = $this->_syarat();
        $tld = $syarat[$this->_tld($domain)];
        foreach($tld as $row){
            $doc[$row]=str_replace("_"," ",strtoupper($row));
        }
        echo json_encode($doc);
    }
    
    public function domain_detail(){
        /**
         * this function is lookup for yaour domain document
        */
        $domain = $_GET['domain'];
        $csrf  = $_GET['token'];
        $id  = $_GET['id'];
        if ($csrf != $_SESSION['csrftoken'] || $csrf == '')
        {
            die('ooops');
        }
        
        $this->validate_input($domain);
        
        $queryuser  = mysql_query("SELECT id from tbldomains where userid = '$id' AND domain = '$domain'");
        $datauser   = mysql_num_rows($queryuser);
        
        if ($datauser == 0)
        {
            die('oops');
        }
        
        $query  = mysql_query("SELECT * from privatensdocument where domain = '$domain'");
        $data   = mysql_fetch_object($query);
        $_syarat = json_decode($data->syarat,true);
        $keys= array_keys($_syarat);
        $detail = null;
        // Document syarat status
        $stat =['Pending','Approved','Rejected'];
        
        foreach($keys as $key){
            $detail[]= [
                'type'   =>str_replace("_"," ",  strtoupper($key)),
                'status' => $stat[$_syarat[$key]['status']],
                'ket'    => ($_syarat[$key]['ket']==null) ? '-': $_syarat[$key]['ket'],
            ];
        }
        
        $domain_detail=$detail;
        echo json_encode($domain_detail);
        
    }
    
    
}
