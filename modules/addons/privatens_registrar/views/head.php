<style>
    .pad-top{
        padding-top: 30px;
    }
</style>
<?php
    
   /*  $str=rand(); 
    $result = sha1($str);
    $csrftoken = $result;
    $_SESSION['csrftoken'] = $csrftoken;
    $_SESSION['adminid'] = $id; */
?>


<form method="post" action='./addonmodules.php?module=privatens_registrar&page=all'>
<div class="col-md-3 col-md-offset-9">
<br>
</div>
</form>
<div class="msg-alert"></div>
<div class="col-md-12">
	<div class="col-md-6 col-xs-12">
		<div class="btn-group">
			<a href="./addonmodules.php?module=privatens_registrar&page=approval" class="btn btn-default"> Need Approval</a>
			<a href="./addonmodules.php?module=privatens_registrar&page=all" class="btn btn-default"> All Documents</a>
		</div>
	</div>
	<div class="col-md-6 col-xs-12">
		<div class="pull-right">
			<button id="btnync" data-toggle="modal" data-target="#modalsync"  class="btn btn-danger"><i class="fas fa-sync"></i> TLD Sync</button>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$( "#synctld" ).click(function() {
		
		$('#modalsync').modal('hide');
		
		$('.msg-alert').html('');
		$("#btnync").attr('disabled','disabled');
		//removeAttr
		$("#btnync i").addClass('fa-spin');
		$.ajax({	
			type: 'POST',
			url: './addonmodules.php?module=privatens_registrar&page=syncTLD',
			data: {},
			dataType: 'json',
			success: function(data){
				if(!data.error){
					$("#btnync").removeAttr('disabled');
					$("#btnync i").removeClass('fa-spin');
					$(".msg-alert").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-ok-circle iconleft" aria-hidden="true"></span> '+data.errorMsg+"</div>");
				}
				else{
					$("#btnync").removeAttr('disabled');
					$("#btnync i").removeClass('fa-spin');
					$(".msg-alert").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-exclamation-sign iconleft" aria-hidden="true"></span> '+data.errorMsg+"</div>");
				}
			}
		});
		return false;
	});
})
</script>
<!-- Modal -->
<div class="modal fade" id="modalsync" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Sync TLD</h4>
		</div>
		<div class="modal-body">
		  <p class="text-center" ><b>Are you sure want to sync?</b></p>
		</div>
		<div class="modal-footer">
		  <button type="button" id="synctld" class="btn btn-info" data-dismiss="modal">OK</button>
		  <button type="button"  class="btn btn-danger" data-dismiss="modal">Cancel</button>
		</div>
	  </div>
	  
	</div>
</div>
