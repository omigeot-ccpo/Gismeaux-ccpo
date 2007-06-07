<?php
function clean_temp($tmp){ 
 $h=opendir($tmp); 
 while($file=readdir($h)){ 
 if($file!="." && $file!=".."){ 
 $date=filemtime("$tmp/$file"); 
 if($date<time()-(600) && is_dir("$tmp/$file")){ 
 $h_in=opendir("$tmp/$file"); 
 while($file_in=readdir($h_in)){ 
 if($file_in!="." && $file_in!=".."){ 
 unlink("$tmp/$file/$file_in"); 
 } 
 } 
 closedir($h_in); 
 rmdir("$tmp/$file"); 
 } 
 elseif($date<time()-(600) && !is_dir("$tmp/$file")&&
is_file("$tmp/$file")){ 
 unlink("$tmp/$file"); 
 } 
 } 
 } 
 closedir($h); 
}
?>