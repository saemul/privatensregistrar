{if $domainname ==''}
	<div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Oh snap!</strong> Access not permitted...!
    </div>
{else}



{if $message['status'] == 'success'}
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Well done!</strong> {$message['messages']}
    </div>
{elseif $message['status'] == 'failed'}
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Oh snap!</strong> {$message['messages']}
    </div>
{/if}
<div class="col-md-12">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4><i class="fa fa-cogs"></i> DNS manager</h4>
			</div>
			<div class="panel-body">
				<p class="alert alert-danger">Apakah anda yakin ingin menggunakan fitur ini ?</p>
				<div class="alert alert-info">
					
					<p>
						Jika anda menggunakan fitur DNS manager ini, nameserver anda akan otomatis berubah menjadi : 
					</p><ul>
						<li>
							armadillo.privatens.id
						</li>
						<li>
							crocodile.privatens.id
						</li>
					</ul>
					<p></p>
				</div>
			</div>
			<div class="panel-footer">
				<center><a id='confirmed' data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing" href="index.php?m=privatens_registrar&domainname={$domainname}&page=dnsmanager&confirm" id="confirm_dns" class="btn btn-primary"> Konfirmasi</a></center>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
        var uri = window.location.toString();
        if (uri.indexOf("&terminate") > 0  || uri.indexOf("&confirm") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("&terminate"));
            var clean_uri2 = uri.substring(0, uri.indexOf("&confirm"));
            window.history.replaceState({}, document.title, clean_uri);
            window.history.replaceState({}, document.title, clean_uri2);
        }
    });
    $("#confirmed").click(function() {
        var $btn = $(this);
        var answer = confirm("Are you sure you want to use this fiture?");
        if(answer){
            $btn.button('loading');
            return true;
        }
        else{
            return false;
        }
        
    });
</script>
{/if}