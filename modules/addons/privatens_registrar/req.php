<?php
    include 'moid.php';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
        $m = new Moid;
        $m->$do();
    }