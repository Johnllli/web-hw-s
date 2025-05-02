<?php
session_start();

//get page from config
$config = require 'config.php';
$pages = $config['pages'];

//def to home
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

//if no page show force to home
if (!array_key_exists($page, $pages)){
    $page = 'home';
};

//make global 
$GLOBALS['config'] = $config;
$GLOBALS['current_page'] = $page;


$protected_pages = ['shop'];

//have to login to check
if(in_array($page, $protected_pages)){
    if(!isset($_SESSION['user_id'])){
        $_SESSION['redirect_url'] = "index.php?page=$page";
    }
}



//change page
$pagefile = $page . '.php';
if (file_exists($pagefile)){
    include($pagefile);
}
else{
    include 'home.php';
}
?>