<?php  
include_once "_start.php";
	require "_funzioni.incl";
	require "_cat_azioni.incl";

	if($dbmusato==""): 
		$contenuto.="<span class=testo>Scegli una tabella da ricercare</span><br><br>";
		
		if($gs=="admin"):
			$handle=opendir('dati');
			while ($file = readdir($handle)):
				if(!is_dir($file)): // ignoro eventuali directory e le due directory per andare su
					$contenuto.= "<a href=\"$PHP_SELF?s=$s&gs=$gs&ln=$ln&dbmusato=$file\">$file</a><br>";
				endif;
			endwhile;
		endif;

		html($contenuto, $messaggio);
	endif;
		
	// verifico se ha il carrello ------------------------------------------------------------
	$dbm = dbmmopen("dati/NAVI","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$nav=unserialize(dbmmfetch($dbm, "6"));			
	dbmmclose($dbm);	
	$hacarrello="no";
	if($nav[POSIZIONE_MENU]>0):
		$hacarrello="si";
	endif;

// DBM di default ------------------------------------------------------------------------
if($dbmusato==""): $dbmusato="CATA"; endif;
if($titolo_della_pagina==""): $titolo_della_pagina=$titolo_pagina; endif;
if($titolo_della_pagina==""): $titolo_della_pagina="Titolo del catalogo"; endif;
$sottotitolo_della_pagina=""; //"Sottotitolo";
$titolo_vis_singolo=""; //"Visualizzazione singolo oggetto"
if($numero_colonne==""): $numero_colonne="1"; endif;
	
// AGGIORNAMENTO upload & replace  ----------------------------------------------
if( ( $az=="aggiorna" && $gs!="" ) || ( $az=="aggiornaA" && $gs!="" ) ): 
	include "_cat_".$dbmusato."aggiornamento.incl"; 	
endif;

// LISTA  -----------------------------------------------------------------------
if($az=="lista"): // elenco tutti in ordine inverso di immissione

	/*
	$campo_per_ordinare="numero_fattura";
	$tiporicerca="campi";
	$camporicerca01="IDoggetto";
	$operatore01="maggiore";
	$stringa01="1";
	$az="rispondi";
	*/
		
	// PRENDO I DATI e li metto in prendo_cata
	// con chiave l'intero array e valore il $campo_per_ordinare
	if($campo_per_ordinare==""): $campo_per_ordinare="IDoggetto"; endif; // il default
	$dbm = dbmmopen("dati/$dbmusato","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$ullo=dbmmfirstkey($dbm);
		while($ullo!=""):
			if($ullo!="campi" && $ullo!="tipi" && $ullo!="nomi" && $ullo!="contatore" && $ullo!="valori" && $ullo!="numero_campi"):
				$valore=dbmmfetch($dbm, $ullo);
				$cata=unserialize($valore);
				if($cata[IDoggetto]!=""): $prendo_cata[$valore]=$cata[$campo_per_ordinare]; endif;
			endif;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;
	dbmmclose($dbm);	
	
	// ORDINO I DATI in base al valore che hanno in $prendo_cata
	if(is_array($prendo_cata)):
		asort($prendo_cata); // li ordino per quello che Ž in valore di $prendo_cata
	endif;
	
	// CONTO I DATI -- per navigazione
	// conto quanti annunci totali ($j) e quanti mostro adesso ($visti) (mimando vera visualizzazione)
	$oggettitrovatitotale=count($prendo_cata);
	if($oggettitrovatitotale!="0"):
		$visti=intval($gia)+1;
		$j=0;
		while ( list( $codice, $val ) = each( $prendo_cata ) ):		
			if( $j>=$gia && $j<($gia+$quanti) && $j<=$oggettitrovatitotale):
				$visti++;
			endif;
			$j++;
		endwhile;
		$visti--; // perche' nell'ultimo giro fa un ++ in piu' 
		if($gia>=$quanti): $precedenti="<span class='testo'><a HREF=$PHP_SELF?&az=lista&s=$s&campo_per_ordinare=$campo_per_ordinare&dbmusato=$dbmusato&gs=$gs&quanti=$quanti&gia=".($gia-$quanti).">precedenti</a></span>"; endif;
		if($gia>=$quanti && $visti<$oggettitrovatitotale): $inmezzo="<span class='testo'> | </span>"; endif;
		if($visti<$oggettitrovatitotale): $prossimi="<span class='testo'><a HREF=$PHP_SELF?&az=lista&s=$s&campo_per_ordinare=$campo_per_ordinare&dbmusato=$dbmusato&gs=$gs&quanti=$quanti&gia=$visti>prossimi</a></span>"; endif;
	endif;

	// METTO LA TESTATA DEL CATALOGO
	$testata_da_usare="_cat_". $dbmusato ."_test.incl";
	if(file_exists("$testata_da_usare")):
		include $testata_da_usare;
	else:
		$contenuto.="<span class=titolo>Testata di $dbmusato</span><br>";
	endif;
	
	// NAVIGAZIONE
	$contenuto.= "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=dddddd><tr>";
	$contenuto.= "<td><span class='testo'><br><br>trovati $oggettitrovatitotale record, visualizzati da ".($gia+1)." a $visti.</span></td>"; 
	$contenuto.= "<td align=right><span class='testo'><br><br>$precedenti $inmezzo $prossimi</span></td>";
	$contenuto.= "</tr></table>";
	$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=500>";

	// MOSTRO I DATI
	$oggettitrovatitotale=count($prendo_cata);
	if($oggettitrovatitotale!="0"):

		$j=0;
		reset($prendo_cata);
		while ( list( $key, $val ) = each( $prendo_cata ) ):
			if( $j>=$gia && $j<($gia+$quanti) && $j<=$oggettitrovatitotale):
				$cata=unserialize($key);
								
				include "_cat_".$dbmusato."_lista.incl";
				
				$elencati++;

			endif;
			$j++;
		endwhile;
	endif;

	
	if($elencati==""):
		$contenuto.="<br><span class='testo'>In questo momento il catalogo e' vuoto</span>";
		if($gs!=""): $contenuto.="<br><a href=\"$PHP_SELF?s=$s&gs=$gs&ln=$ln&dbmusato=$dbmusato&az=nuovo\">nuovo oggetto</a>"; endif;
	endif;

	// NAVIGAZIONE
	$contenuto.= "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=dddddd><tr>";
	$contenuto.= "<td><span class='testo'><br><br>trovati $oggettitrovatitotale record, visualizzati da ".($gia+1)." a $visti.</span></td>"; 
	$contenuto.= "<td align=right><span class='testo'><br><br>$precedenti $inmezzo $prossimi</span></td>";
	$contenuto.= "</tr></table>";

	html($contenuto, $messaggio);

endif;

// RISPOSTA ALLA RICERCA  --------------------------------------------------------------
if($az=="rispondi"): //se abbiamo ricerca in corso
	include "_ric_trova.incl";
	include "_ris_conto.incl";
	include "_ris_navigazione.incl";
	include "_ris_lista.incl";
	
	html($contenuto, $messaggio);
endif;

// VISUALIZZAZIONE e MODIFICA SINGOLO OGGETTO  ------------------------------------------------------
if($az=="singolo"): //se ne vuole vedere o modificare uno solo
		
	$singolo_da_usare="_cat_". $dbmusato ."_singolo.incl";	
	if(!file_exists("$singolo_da_usare")):
		// mostro il singolo in condizioni di emergenza, senza il file specifico per questa tabella
		$contenuto.= "<span class='titolo'><b>Visualizzazione singolo oggetto in $dbmusato</b></span><br><br>";
		// PRENDO I DATI
		$dbm = dbmmopen("dati/$dbmusato","r");
			$cata=unserialize(dbmmfetch($dbm,"$IDoggetto"));
			$c_cata=unserialize(dbmmfetch($dbm,"campi"));
			$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
			$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
			$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		dbmmclose($dbm);	
	
		$contenuto.= "<div align=right>";	
		for ($j = 1; $j <= $c_cata[numero_campi]; $j++):
 			$nome_campo=$c_cata[$j];
			if($t_cata[$nome_campo]=="t"):  $contenuto.= "<B>$n_cata[$nome_campo]</B>: $cata[$nome_campo]<br>"; endif;
			if($t_cata[$nome_campo]=="b"):  $contenuto.= "<B>$n_cata[$nome_campo]</B>: $cata[$nome_campo]<br>"; endif;
			if($t_cata[$nome_campo]=="f"):  $contenuto.= "<img src='$cata[$nome_campo]?'><br>"; endif;
		endfor;
		$contenuto.= "</div>";
	else:
		// in condizioni normail uso il record specifico per visualizzare i dati della tabella
		include $singolo_da_usare; 
	endif;
	
	html($contenuto, $messaggio);
endif;

// se arriva qui non ci sono ricerche in corso -------------------------------------------------
	$impostazioni_da_usare="_cat_". $dbmusato ."_imp.incl";
	if(!file_exists("$impostazioni_da_usare")):
		// imposto ricerca in condizioni di emergenza, senza il file specifico per questa tabella
		include "_ric_imp.incl";
	else:
		include $impostazioni_da_usare;
	endif;
	
	html($contenuto, $messaggio);
?>
