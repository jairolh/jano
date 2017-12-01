<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
?>

<script type='text/javascript'>

function desbloquea(bloq,ver) {
    visible(ver);
    novisible(bloq); 
    
}   

function visible(ver) {
    obj = document.getElementById(ver);
    obj.style.display = 'block';
    //obj.style.display = (obj.style.display=='none') ? 'block' : 'none';
} 

function novisible(bloq) {

    obj2 = document.getElementById(bloq);
    obj2.style.display = 'none';
    //obj2.style.display = (obj2.style.display=='none') ? 'block' : 'none';
} 

</script>