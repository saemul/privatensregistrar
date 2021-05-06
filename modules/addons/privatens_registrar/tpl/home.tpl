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
        max-height: 500px;
        overflow-y: scroll;
    }
</style>
<link rel="stylesheet" href="./assets/css/dropzone.css">
<div class="container-fluid">
    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a href="./index.php?m=privatens_registrar">Upload Document</a></li>
      <li role="presentation"><a href="./index.php?m=privatens_registrar&page=requirement">Domain Document</a></li>
    </ul>
</div>
<div class="container-fluid">
	<div class="row">
	  
	    
	   <blockquote>
	        <span class="text-muted"> Here you can upload multiple file</span>
	   </blockquote>
    	<form action="./index.php?m=privatens_registrar&page=upload" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
          <div class="fallback">
            <input name="file" type="file" multiple />
          </div>
        </form>
      

	</div>
	<div class="row">
	        <h4> Your Files</h4>
	        <div class='text-center'>{$msg}</div>
	        <blockquote>
	            <span class="text-muted"> this all document is your document, and it can be used for all domain without reupload any document</span>
	        </blockquote>
	        <br>
	        <div class="col-md-12">
	            
	        
	        <div class="scroll">
				<div class="row">
	              {foreach from=$document item=row}
            	    <div class="col-md-2 col-xs-12"
            	        <div class="panel panel-default">
            	            <div class="panel-body">
            	                <a href ='#' data-toggle="modal" data-target="#myModal" class="load" data-id="{$dir}{$row->file}" data-name='{$row->file}'>
                    	            <img src={$dir}{$row->file}  width="100" height="100"/>
                    	        </a>        
            	            </div>
            	            <div class="panel-footer">
            	                <div class="btn-group btn-group-justified">
            	                    <a class='remove btn btn-danger' href="./index.php?m=privatens_registrar&page=remove&file={$row->file}"> Delete</a>
            	                </div>
            	            </div>
            	        </div>
        	     {/foreach}
				</div>
	        </div>
			
			</div>
	</div>
</div>
{$domains}

<form method="post" action ="./index.php?m=privatens_registrar&page=set_doc">
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Image As</h4>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-8">
                   <img id="modal_prev" width="100%" height="auto"/> 
              </div>
              <div class="col-md-4">
                   <br>
                    <input type="hidden" name="file" id="file_set">
                    <p>Domain Name</p>
                    <select name="domain" class='form-control' id="select_domain" required>
                         <option value="">-Choose Domain-</option>
                        {foreach from=$domains item=row}
                        <option value="{$row}">{$row}</option>
                         {/foreach}
                    </select>
                    <br>
                     <p>Document Type</p>
                     <select name='type' class="form-control" id="doc_domain" required>
                         <option value="">-Choose Type-</option>
                     </select>
                     <br>
                     <p>This Domain Need</p>
                     <blockquote id="keterangan">
                         
                     </blockquote>
                     <br>
                     <input name="set_all" type="checkbox" /> Set this document for all domain
              </div>
          </div>
         
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-lg" >Set</button>
        <a type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</a>
      </div>
    </div>

  </div>
</div>
</form>


<script src="./modules/addons/privatens_registrar/assets/dropzone.js"></script>

<script>



$('.remove').click(function(){
  if(!confirm("Are you sure want to delete document ?")){
      return false;
  }  
})
$('.load').click(function(){
    var url = $(this).attr("data-id");
    var file = $(this).attr('data-name');
    $('#modal_prev').attr("src",url);
    $('#file_set').val(file);
})
    

$(function(){
  Dropzone.options.myAwesomeDropzone = {
    maxFilesize: 5,
    addRemoveLinks: true,
    dictResponseError: 'Server not Configured',
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
    init:function(){
      var self = this;
      // config
      self.options.addRemoveLinks = true;
      self.options.dictRemoveFile = "Delete";
      //New file added
      self.on("addedfile", function (file) {
        console.log('new file added ', file);
      });
      // Send file starts
      self.on("sending", function (file) {
        console.log('upload started', file);
        $('.meter').show();
      });
      
      // File upload Progress
      self.on("totaluploadprogress", function (progress) {
        console.log("progress ", progress);
        $('.roller').width(progress + '%');
      });

      self.on("queuecomplete", function (progress) {
        $('.meter').delay(999).slideUp(999);
            window.location.href ='./index.php?m=privatens_registrar';
      });
      
      // On removing file
      self.on("removedfile", function (file) {
        console.log(file);
        window.location.href ='./index.php?m=privatens_registrar';
      });
    }
  };
})



$('#select_domain').change(function(){
    var domain = $(this).val();
    var token = '{php} echo $csrftoken; {/php}'
    var id = '{php} echo $id; {/php}'
    var url = './modules/addons/privatens_registrar/req.php?do=lookup_tld&domain='+domain+'&token='+token+'&id='+id;
    $.get(url,function(data){
       var obj = JSON.parse(data);
       $.each(obj,function(i,v){
         $('#doc_domain')
         .append($("<option></option>")
                    .attr("value",i)
                    .text(v)); 
       })
       
       $.each(obj,function(i,v){
         $('#keterangan')
         .append($("<p></p>")
         .text(v)); 
       })
    })
})


</script>
