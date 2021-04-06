<div class="col-md-12 pad-top">
    <table class="table table-bordered">
        <thead class="bg-primary">
            <th>Domain</th>
            <th>Client</th>
            
            <th>File Need Approve</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php foreach($table as $t):?>
            <tr>
                <td><?=$t->domain;?></td>
                <td>
                    <?=$t->client_name;?>
                    <p>(<?=$t->client_email;?>)</p>
                </td>
               
              <td><?=$t->file;?> File Need Approve</td>
                <td width="10%">
                    <a class="btn btn-success btn-block " data-toggle="modal" data-target="#myModal" data-id="<?=$t->domain;?>"> Proccess</a>
                   
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close _reload" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Document <span id='domain_name'></span></h4>
      </div>
      <div class="modal-body">
       
      </div>
      
    </div>

  </div>
</div>

<script>
    $(document).ready(function(){
        $('table').DataTable();
    })
    
    // get preview
    $('.btn-success').click(function(){
        var domain = $(this).attr('data-id');
        var token = '<?php echo $_SESSION['csrftoken']; ?>'
        var url ='https://'+'<?=$_SERVER['SERVER_NAME'];?>'+'/modules/addons/privatens_registrar/req.php?do=domain_document';
        $.post(url,{domain:domain,token:token},function(data){
            $('#domain_name').html(domain)
            $('.modal-body').html(data);
        })
		//return false;
    })
    
    $('.close').click(function(){
        location.reload();
    })
    
</script>