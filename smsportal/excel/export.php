<?php


include 'db.php';
global $con;
$SQL = "SELECT  `id`,`from`,`text` from sms";
$header = '';
$result ='';
$exportData = mysqli_query($con,$SQL) ;
$header .="ID" . "\t";;
$header .="from" . "\t";;
$header .="text" . "\t";;

if (mysqli_num_rows($exportData) > 0) 
							{
 
while( $row = mysqli_fetch_assoc( $exportData ) )
{
	
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $result .= trim( $line ) . "\n";
}
$result = str_replace( "\r" , "" , $result );
 
if ( $result == "" )
{
    $result = "\nNo Record(s) Found!\n";                        
}
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$result";

							}
 
?>