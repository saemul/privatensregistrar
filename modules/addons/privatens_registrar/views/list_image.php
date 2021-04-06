<?php
    $str=rand(); 
    $result = sha1($str);
    $csrftoken = $result;
    $_SESSION['csrftoken'] = $csrftoken;
?>
<?php 
    foreach ($doc as $d):
    $keys = array_keys((array)$d);
    
    $key = $keys[0];
;?>
<div class="row">
  
    <img class="img img-responsive" src="https://<?=$_SERVER['SERVER_NAME'];?>/modules/addons/privatens_registrar/files/<?=$d[$key]->file;?>">
</div>
<br>
<label>Notes For Client</label>
<textarea class="form-control" id='ket-<?=$key;?>'></textarea>
<br>
<div class='panel-footer conteiner text-center btn-group-justified'>
    <a href="#" class="proccess btn btn-success btn-lg" id="<?=$key;?>" data-status='1'><i class="fa fa-check"></i> Approve </a>
    <a href="#" class='proccess btn btn-danger btn-lg reject' id ='<?=$key;?>' data-status='2'><i class="fa fa-remove"></i> Reject </a>
</div>

<?php endforeach;?>

<script>
    $('.proccess').click(function(){
         var key = $(this).attr('id');
         var token = '<?php echo $csrftoken; ?>'
         var status = $(this).attr('data-status')
         var domain = '<?=$domain;?>';
         var arr ={
             domain : domain, 
             key    : key,
             token  : token,
             status : status,
             ket    : $('#ket-'+key+'').val()
         }
         _proccess(arr);
    })
 
    
    
    function _proccess(arr){
        
        var url ='https://'+'<?=$_SERVER['SERVER_NAME'];?>'+'/modules/addons/privatensdocument/req.php?do=proccess_doc';
         $.post(url,arr,function(data){
             _request_reload(arr.domain,arr.token);
         })
    }
    
    
    function _request_reload(domain,token){
        var url ='https://'+'<?=$_SERVER['SERVER_NAME'];?>'+'/modules/addons/privatensdocument/req.php?do=domain_document';
        $.post(url,{domain:domain,token:token},function(data){
           
            $('.modal-body').html(data);
        })
    }
    
     
   
</script>