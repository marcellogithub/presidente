<?php   
// database usato: PAGI
// passo i parametri dall'url

include_once "_start.php";

//	$IDoggetto="2"; // corrisponde alla pagina chisiamo.php
//	$IDoggetto="3"; // corrisponde alla pagina dovesiamo.php
//	$IDoggetto="4"; // corrisponde alla pagina prodottieservizi.php

	if ($numero_blocchi==""): $numero_blocchi=10; endif;
	if ($dbmusato==""): $dbmusato="PAGI"; endif;
	if ($IDoggetto==""): $IDoggetto="1"; endif;

	include "_funzioni.incl";
	require "_cat_azioni.incl";


// DEFAULT: MOSTRO PAGINA $IDoggetto ---------------------------------------------
	// PRENDO I DATI
	$dbm = dbmmopen("dati/PAGI","r");
		$pagi=unserialize(dbmmfetch($dbm,"$IDoggetto"));
		$c_pagi=unserialize(dbmmfetch($dbm,"campi"));
		$t_pagi=unserialize(dbmmfetch($dbm,"tipi"));
		$n_pagi=unserialize(dbmmfetch($dbm,"nomi"));
	dbmmclose($dbm);	

	$pagi[titolo]=stripslashes($pagi[titolo]);
	$pagi[sottotitolo]=stripslashes($pagi[sottotitolo]);

	$mostrotitolo=nl2br($pagi[titolo]);
	$mostrosottotitolo=nl2br($pagi[sottotitolo]);
	
	$contenuto.= "<table width='100%' border=0 cellpadding=0 cellspacing=0>";		
	$contenuto.= "<tr><td valign=top>";		
	if($pagi[titolo]!=""): $contenuto.= "<a class='titolo'>$mostrotitolo</a><br>"; endif;
	if($pagi[sottotitolo]!=""): $contenuto.= "<a class='titoletto'>$mostrosottotitolo</a><br>"; endif;
	$contenuto.= "<br>";		
	$contenuto.= "<td></tr>";		
	for($x=1;$x<($numero_blocchi+1);$x++)
	{
		$testo="testo0".$x;
		$foto="foto0".$x;
		$pagi[$testo]=stripslashes($pagi[$testo]);
		$contenuto.= "<tr><td valign=top>";		
		if(file_exists($pagi[$foto]) && trim($pagi[$foto])!=""): $contenuto.= "<img SRC='$pagi[$foto]' align=right HSPACE='10' VSPACE='3' BORDER='0'>"; endif;
		if($pagi[$testo]!=""): $contenuto.= "<span class='testo'>".nl2br($pagi[$testo])."</span><br><br>"; endif;
		$contenuto.= "<td></tr>";
	}

	html($contenuto, $messaggio);
?>