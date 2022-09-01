{if $domainname ==''}
	<div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Oh snap!</strong> Access not permitted... test!
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
<div class="panel panel-default">
<div class="panel-heading">
    <h4>DNS Manager</h4>
    <span class=" pull-right">
        <a id='terminated' data-loading-text="<i class='fa fa-spinner fa-spin '></i> Terminating" href="index.php?m=privatens_registrar&id={$smarty.get.id}&domainname={$smarty.get.domainname}&page=dnsmanager&terminate" class="btn btn-warning"><i class="fa fa-ban"></i> Terminate All Record</a>
    </span>
    </h4>
    <div class="clearfix"></div>

</div>
<div class="panel-body">
    <table class="table table-condensed table-striped">
        <thead class="bg-primary">
            <th>HostName</th>
            <th>Type</th>
            <th>Ttl</th>
            <th>Value</th>
            <th>#Action</th>
        </thead>
        <tbody>
            {foreach $dns->data->zone[0]->record as $value}
                {if $value->name}
                    <tr>
                        <td><input type='text' class='form-control' value='{$value->name}'></td>
                        <td><input type='text' class='form-control' value='{strtoupper($value->type)}'></td>
                        <td><input type='text' class='form-control' value='{$value->ttl}'></td>
                        <td>
                            {if $value->exchange } <input type='text' class='form-control' value='{$value->exchange}'>{/if}
                            {if $value->address } <input type='text' class='form-control' value='{$value->address}'> {/if}
                            {if $value->nsdname } <input type='text' class='form-control' value='{$value->nsdname}' > {/if}
                            {if $value->raw } <input type='text' class='form-control' value='{$value->raw}'> {/if}
                            {if $value->cname } <input type='text' class='form-control' value='{$value->cname}'> {/if}
                            {if $value->preference } <input type='text' class='form-control' value='{$value->preference}'> {/if}
                            {if $value->txtdata } <input type='text' class='form-control' value='{$value->txtdata}'> {/if}
                        </td>
                        <td><a href='index.php?m=privatens_registrar&domainname={$domainname}&page=dnsmanager&delete&ids={$value->Line}' class='btn btn-danger deleted' data-loading-text="<i class='fa fa-spinner fa-spin '></i> Deleting"><i class="fa fa-trash"></i> Delete</a></td>
                    </tr>
                {/if}
            {/foreach}
        </tbody>
    </table>
     <form id='form_privatensdns' method="post" action='index.php?m=privatens_registrar&id={$smarty.get.id}&domainname={$smarty.get.domainname}&page=dnsmanager&add'>
      
        <table class="table table-condensed table-striped">
            <tbody>
                <tr>
                     
                    <input type='hidden' name="domain" class='form-control' value='{$domainname}'>
                     
                    
                     <td>
                         <input name="name" class='form-control' placeholder='Hostname'>
                     </td>
                     <td>
                         <select name="type" class="form-control" id="select-type" onchange="changeValue(this)">
						    <option value="A">A</option>
						    <option value="AAAA">AAAA</option>
							<option value="CNAME">CNAME</option>
							<option value="MX">MX</option>
							<option value="TXT">TXT</option>
							<option value="NS">NS</option>
						</select>
                     </td>
                      
                      <td>
                         <input name="ttl" class='form-control' placeholder='ttl'>
                     </td>
                      <td id="add-container">
                         <input id="address" name="values[address]" class='form-control' placeholder='Address'>
                         <input id="cname" name="values[cname]" class='form-control' placeholder='Cname'>
                         <input id="preference" name="values[preference]" class='form-control' placeholder='Preference'>
                         <input id="exchange" name="values[exchange]" class='form-control' placeholder='Exchange'>
                         <input id="txtdata" name="values[txtdata]" class='form-control' placeholder='Txtdata'>
                         <input id="nsdname" name="values[nsdname]" class='form-control' placeholder='Nsdname'>
                     </td>
                </tr>
                <tr>
                    <td colspan='6'>
                        <center><button id='add' class='btn btn-primary' type='submit' data-loading-text="<i class='fa fa-spinner fa-spin '></i> Adding DNS Record"><i class="fa fa-plus"></i> Add New</button></center>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
</div>
<script>
    $(document).ready(function(){
        var uri = window.location.toString();
        if (uri.indexOf("&add") > 0 || uri.indexOf("&delete&ids") > 0 || uri.indexOf("&confirm") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("&add"));
            var clean_uri2 = uri.substring(0, uri.indexOf("&delete&ids"));
            var clean_uri3 = uri.substring(0, uri.indexOf("&confirm"));
            window.history.replaceState({}, document.title, clean_uri);
            window.history.replaceState({}, document.title, clean_uri2);
            window.history.replaceState({}, document.title, clean_uri3);
        }
    });
    $("#add").click(function() {
        var $btn = $(this);
        $btn.button('loading');
        
    });
    
    function changeValue(el){
        $('#add-container input').hide()
        
        switch(el.value.toLowerCase()) {
            case "a" :
                $('#add-container').find('#address').show()
                break;
            case "aaaa" :
                $('#add-container').find('#address').show()
                break;
            case "cname" :
                $('#add-container').find('#cname').show()
                break;
            case "mx" :
                $('#add-container').find('#exchange').show()
                $('#add-container').find('#preference').show()
                break;
            case "txt" :
                $('#add-container').find('#txtdata').show()
                break;
            case "ns" :
                $('#add-container').find('#nsdname').show()
                break;
                
        }
        
    }
    
    changeValue($('#select-type')[0])

    $(".deleted").click(function(){
        var $btn = $(this);
        var answer = confirm("Are you sure you want to delete this record?");
        if(answer){
            $btn.button('loading');
            return true;
        }
        else{
            return false;
        }
    });

    $("#terminated").click(function() {
        condole.log('hasawao');
        var $btn = $(this);
        var answer = confirm("Are you sure you want to terminate all record?");
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
