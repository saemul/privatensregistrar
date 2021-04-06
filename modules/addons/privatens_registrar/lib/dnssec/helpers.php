<?php
function renderError($url,$id,$domain, $error){
    return array(
        'pagetitle' => 'Invalid Process',
        'breadcrumb' => array($url=>'Error Message'),
        'templatefile' => 'lib/dnssec/tpl/client_error',
        'requirelogin' => true, # accepts true/false
        'forcessl' => true, # accepts true/false
        'vars' => array(
            'id'     => $id,
            'domain' => $domain,
            'error'  => $error
        )
    ); 
}