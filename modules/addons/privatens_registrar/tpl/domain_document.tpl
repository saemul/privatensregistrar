{php}
    $str=rand(); 
    $result = sha1($str);
    $csrftoken = $result;
    $_SESSION['csrftoken'] = $csrftoken;
    $id = $_SESSION['uid'];
{/php}
<style>
    img{
        display: block;
        margin-left: auto;
        margin-right: auto 
    }
    .scroll{
        width: 100%;
        height: 500px;
        overflow-y: scroll;
    }
    .dataTables_info{
        color :white !important;
        border-radius: 0 px !important;
    }
</style>
<link rel="stylesheet" href="./modules/addons/privatens_registrar/assets/source/css/jquery.dataTables.min.css">
<div class="container-fluid">
    
    <ul class="nav nav-tabs">
      <li role="presentation" ><a href="./index.php?m=privatens_registrar">Upload Document</a></li>
      <li role="presentation" class="active"><a href="./index.php?m=privatens_registrar&page=requirement">Domain Document</a></li>
    </ul>
</div>

<div class='container'>
    <div class="row">
      <br>
    </div>
    <div class='row'>
        <table id="example" class="display tbl" style="width:100%">
        <thead>
            <tr>
                <th>Domain Name</th>
                <th>Document</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
              {foreach from=$table item=row}
            <tr>
                <td>{$row->domain}</td>
                <td>{$row->count} Files</td>
                <td>{$row->status}</td>
                <td>
                    <a hred="" data-domain='{$row->domain}' class="btn btn-grey btn-block detail" data-toggle="modal" data-target="#myModal" ><i class="fa fa-search"></i> Details</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
        
    </table>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Domain Document (<span id="domain_name"></span>)</h4>
      </div>
      <div class="modal-body">
        <table id="tbls">
            <thead class='table' >
                <th>Document Type</th>
                <th>Status</th>
                <th> Reason</th>
            </thead>
            <tbody id="detail_document">
              
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script src="./modules/addons/privatens_registrar/assets/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
         $('#tbls').DataTable();
        
        
    });
    
     // detail document 
    $('.detail').click(function(){
        var domain = $(this).attr('data-domain')
        var token = '{php} echo $_SESSION['csrftoken']; {/php}'
        var id = '{php} echo $id; {/php}'
        $('#domain_name').html(domain) 
        
        var url = './modules/addons/privatens_registrar/req.php?do=domain_detail&domain='+domain+'&token='+token+'&id='+id;
        
        $.get(url,function(data){
            $('#detail_document').html("")
            var obj = JSON.parse(data);
            $.each(obj,function(i,v){
                $('#detail_document').append("<tr><td>"+v.type+"</td><td>"+v.status+"</td><td>"+v.ket+"</td></tr>")
            })
        })
       
    });
   
    
</script>