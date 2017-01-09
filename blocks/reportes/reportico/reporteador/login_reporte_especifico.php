<?php
//inicio desde menus
    if(isset($_REQUEST['informes']))
        {   $_REQUEST['jump_to_language']='es_es';
            $proyecto=$_REQUEST['informes'];    	
            $pass=$_REQUEST['acceso']; 
            //variables para direccionar a reporte especifico
            if(isset($_REQUEST['reporte']) && $_REQUEST['reporte']!='')
                {$reporte=  str_replace('\\', '', $_REQUEST['reporte']); 
                 $_REQUEST['xmlin']=  $reporte.".xml";
                 $_REQUEST['execute_mode']='PREPARE';
                 $_REQUEST['project']=$proyecto;
                }
           else {$_REQUEST['jump_to_menu_project']=$proyecto;
                 $_REQUEST['submit_menu_project']='Ejecutar';
                }
            $_REQUEST['project_password']=  $pass;
            $_REQUEST['clear_session']='yes';
            
        }
    else{ $proyecto=$_REQUEST['project'];  }
          
    if($proyecto=='admin')
        {$_REQUEST['access_mode']='FULL';
         $_REQUEST['admin_password']=$_REQUEST['project_password'];
         $_REQUEST['login']='Acceder';
        }
    else{$_REQUEST['access_mode']='ONEREPORT';}           
           

?>
