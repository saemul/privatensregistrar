<link rel="stylesheet" href="./modules/addons/privatensdocument/assets/source/css/jquery.dataTables.min.css">
<div class="table-reponsive">
    <div class="col-md-12" style="padding-top:20px" >
        	<table id="tbl_syarat" class="table table-condensed">
    		<thead class="bg-primary">
    			<th> Client</th>
    			<th> Total File</th>
    			<th> Detail </th>
    		</thead>
    		<tbody>
    		    <?php while($row = mysql_fetch_object($db_data)){ ?>
    			<tr>
    				<td>
    				    <b> <i class="glyphicon glyphicon-user"></i> <?=$row->firstname;?> | <i class="glyphicon glyphicon-home"></i> <?=$row->companyname;?></b>
    				    <blockquote style="font-size:12px">
    				         <p><i class="glyphicon glyphicon-envelope"></i> <?=$row->email;?></p>
    				    		<p><i class="glyphicon glyphicon-phone"></i> <?=$row->phonenumber;?></p>
    				    </blockquote>
    				</td>
    				<td><?=$row->jumlah;?> Files</td>
    				<td>
    				   <a href="./addonmodules.php?module=privatens_registrar&page=document_client&userid=<?=$row->id;?>" class="page_detail"> Detail</a>
    				</td>
    			</tr>
    			<?php } ;?>
    		</tbody>
    	</table>
    
    </div>
</div>
<script src="./modules/addons/privatensdocument/assets/datatables.min.js"></script>
<script>
    $(document).ready(function(){
        $('#tbl_syarat').DataTable();
    })
</script>