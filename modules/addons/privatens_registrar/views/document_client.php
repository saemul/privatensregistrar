<div class="container" >
    <div class='row' style="padding-top:100px">
        <?php while($row = mysql_fetch_object($query)) { ?>
            <a target='__blank' href="https://<?=$_SERVER['SERVER_NAME'];?>/modules/addons/privatens_registrar/files/<?=$row->file;?>">
                <div class='col-md-2'>
                    <img src="https://<?=$_SERVER['SERVER_NAME'];?>/modules/addons/privatens_registrar/files/<?=$row->file;?>" width="100%" height="150px">
                </div>
            </a>
        <?php } ;?>
    </div>
</div>