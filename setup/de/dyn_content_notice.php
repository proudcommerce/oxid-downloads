<?php
     require_once "lang.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
    <title><?php echo( $aLang['HEADER_META_MAIN_TITLE'] ) ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo( $aLang['charset'] ) ?>">
    <style type="text/css">
       body, p, td, tr, ol, ul, input, textarea {font:11px/130% Trebuchet MS, Tahoma, Verdana, Arial, Helvetica, sans-serif;}
    </style>
</head>    

<body>
     <p>
     <?php
        echo $aLang['LOAD_DYN_CONTENT_NOTICE'];
     ?>
     </p>
</body>
    
</html>
