<?php  

// PRENDO I DATI SITO =================== ================== ==================
$dbm = dbmmopen("dati/IDEK","r");
	$idek=unserialize(dbmmfetch($dbm,"1"));
dbmmclose($dbm);
if($stile_locale!=""): $idek[STILESITO]=$stile_locale; endif;
$dbm = dbmmopen("dati/STIL","r");
	$stil=unserialize(dbmmfetch($dbm,$idek[STILESITO]));
dbmmclose($dbm);

echo "<html>";
echo "<head>";

include "htm_css.php";

echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
echo "<meta name='robots' content='all'> ";
echo "<meta name='resource-type' content='document'>";
echo "<meta name='description' content='$idek[DESCRIZIONESITO]'>";
echo "<meta name='keywords' content='$idek[PAROLECHIAVESITO]'>";
echo "<meta name='copyright' content='CommpoSiti 2001-2004'>";
echo "<meta name='publisher' content='CompoSiti'>";
echo "<meta name='author' content='Marcello Balbo'>";

echo "<title>$idek[TITOLOSITO]</title>";
echo "</head>";

echo "<body 
	bgcolor='$stil[COLORE_SFONDO_A]'
	background='$stil[IMMAGINE_SFONDO_A]'
	marginheight='0'
	marginwidth='0'
	topmargin='0'
	leftmargin='0'
	rightmargin='0'
	text='$stil[COLORE_TESTO]'
	link='#0033CC'
	vlink='#660099'
	alink='#996699' >
	";
	
	//se e' in pannello metto fascia pannello
	if($gs!=""): 
		echo "<!-- GESTIONE -->";
		echo "<table BORDER='0' CELLSPACING='2' CELLPADDING='5' width='100%'>";
		echo "<tr ALIGN='Center' VALIGN='top'>";
			echo "<td bgcolor='#999966'  ALIGN='center' VALIGN='top'>";
			echo "<a HREF='pagina.php?IDoggetto=1&ln=$ln'><b>ESCI DAL PANNELLO</b></a>";
			$dbm = dbmmopen("dati/SESS","r");
				$sess_temp=unserialize(dbmmfetch($dbm,$s));
			dbmmclose($dbm);
			if (ermeg("B", $sess_temp[permessi]) && ermeg("b", $sess_temp[permessi]))	//controllo se l'utente può vedere la pagina indexgs
			{
				echo "&nbsp;|&nbsp;<a HREF='indexgs.php?s=$s&gs=$gs&ln=$ln''><b>home PANNELLO speciale</b></a>";
			}
			else
			{
				echo "&nbsp;|&nbsp;<a HREF='pagina.php?s=$s&gs=$gs&ln=$ln&IDoggetto=1''><b>home PANNELLO speciale</b></a>";
			}
			echo "</td>";
		echo "</tr>";
		echo "</table>";
	endif;
?>