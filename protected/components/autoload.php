<?php
$dir = dirname(__FILE__);
$dirh=opendir($dir);

while($file=readdir($dirh)){
    if(strpos($file, 'Trait') === 0){
        require_once("$dir/$file");
    }
}
