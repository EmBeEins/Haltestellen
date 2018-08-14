<!DOCTYPE html>
<html lang="en">
<head>
  <title>Abfahrtstafel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
$haltestelle=$_GET["haltestelle"];
if (!$haltestelle=$_GET["haltestelle"]){echo "<h1>Keine Haltstelle gefunden</h1>Bitte versuchen Sie es erneut... klicken Sie <a href='index.php'>hier</a>";exit;}

$url="http://efa.vrr.de/standard/XSLT_DM_REQUEST?language=de&itdLPxx_dmRefresh=&typeInfo_dm=stopID&nameInfo_dm=$haltestelle&useRealtime=1&mode=direct&outputFormat=JSON";
$json1 = file_get_contents($url);
$data1 = json_decode($json1);
$Haltestellex = $data1->dm->points->point->name;
?>

<div class="container">
  <h3>Abfahrtstafel <?php echo $Haltestellex;?></h3>
  <p></p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Von</th>
        <th>Nach</th>
        <th>Art</th>
        <th>Gleis</th>
        <th>Nummer</th>
        <th>Geplant</th>
        <th>Unternehmen</th>
      </tr>
    </thead>
    <tbody>
<?php
// 24011186 (Kamen)
// 24017616 (Kamen-Markt)
// 24017679 (Kamen-Siegeroth)
// 24011188 (Kamen-Methler)
// 20000131 (Dortmund)
$url="http://efa.vrr.de/standard/XSLT_DM_REQUEST?language=de&itdLPxx_dmRefresh=&typeInfo_dm=stopID&nameInfo_dm=$haltestelle&useRealtime=1&mode=direct&outputFormat=JSON";
$json = file_get_contents($url);
$data = json_decode($json);
$ADSBs = $data->departureList;
$i = 0;
foreach ($ADSBs as $ADSB) {
  $uhrzeit=str_pad($ADSBs[$i]->dateTime->hour, 2, 0, STR_PAD_LEFT).":".str_pad($ADSBs[$i]->dateTime->minute, 2, 0, STR_PAD_LEFT).""; 

  $uhrzeit2=str_pad($ADSBs[$i]->realDateTime->hour, 2 ,'0', STR_PAD_LEFT).":".str_pad($ADSBs[$i]->realDateTime->minute, 2 ,'0', STR_PAD_LEFT);

if (0==$ADSBs[$i]->servingLine->delay){$delay="";}else{$delay="<font color='red'> +".$ADSBs[$i]->servingLine->delay."&nbsp;Min.</font>";}

echo"<tr>";
echo"<td>".$ADSBs[$i]->servingLine->directionFrom."</td>";
echo"<td>".$ADSBs[$i]->servingLine->direction."</td>";
echo"<td>".$ADSBs[$i]->servingLine->name."</td>";
echo"<td>".$ADSBs[$i]->platformName."</td>";
echo"<td>".$ADSBs[$i]->servingLine->number."</td>";
echo"<td>".$uhrzeit."".$delay."</td>";
echo"<td>".$ADSBs[$i]->operator->name."</td>";
echo"</tr>";
 //echo "<br>".$i.":".$ADSBs[$i]->stopName." //".$ADSBs[$i]->servingLine->name."";;

$i++;
}

?>
     </tbody>
  </table> 
<hr>
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Datenquelle: <a href='http://www.vrr.de' target='_blank'>VRR</a>&nbsp;<br><a href="https://www.marc-brandt.de/dsgvo.html" target="_blank">Impressum&nbsp;&amp;&nbsp;Datenschutzerkl&auml;rung</a>
      </div>
    </footer>
