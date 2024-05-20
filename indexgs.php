<?php   
// ROGNE
include_once "_start.php";

	$ps="b"; // per accedere occorre avere il permesso "b" in $utente[permessi]
	$pgs="B"; // per accedere occorre avere il permesso "B" in $utente[permessi]

	include "_funzioni.incl";
	require "_cat_azioni.incl";

// per essere sicuro che funzioni
if($gs==""): $gs="prova"; endif;

$contenuto.= "<div align=right>";
$contenuto.= "<a class='testo' color='#cccccc'>dbm: $dbmusato</a> | ";
$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az='>pannello</a>";
$contenuto.= "</div>";
	
// PANNELLI AUSILIARI  ---------------------------------------------------------------

// GESTIONE ACQU - EFFETTUO CONTROLLO (prima di immissione) --------------------------
if($az=="controlla_acqu"): //non faccio controlli al momento
	$dbm = dbmmopen("dati/NAVI","r");
		$navi=unserialize(dbmmfetch($dbm,"5"));
		$acqu=unserialize($navi[PARAMETRI]);
	dbmmclose($dbm);

	$az="immetti_acqu"; // quando controllo va bene, faccio immissione
endif;

// GESTIONE ACQU - IMMETTO valori  ---------------------------------------------------
if($az=="immetti_acqu"):
	
	// IMMISSIONE DATI
	$dbm = dbmmopen("dati/NAVI","w");
		$navi=unserialize(dbmmfetch($dbm,"5"));
		$navi[PARAMETRI]=serialize($new_acqu);
		dbmmreplace($dbm, "5", serialize($navi));  
	dbmmclose($dbm);	
			
	$contenuto.= "<BR><a class='titolo'>valori aggiornati</a><BR><hr>";
	$az="gestione_acqu"; // cos“ la edita
	$IDoggetto="5"; // cos“ la edita
endif;

// GESTIONE DEFAULT parametri ACQU ----------------------------------------
if($az=="gestione_acqu"):
	$contenuto.= "<a class='titolo'>Gestione modalit&aacute; acquisto</a>&nbsp;|&nbsp;
	<a href='' onclick=\"window.open('help.php?pg=XXX', '', 'toolbar=no,location=no,directory=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=300' );return false;\">help</a><br>";
		
	// PRENDO I DATI
	$dbm = dbmmopen("dati/NAVI","r");
		$navi=unserialize(dbmmfetch($dbm,"5"));
		$acqu=unserialize($navi[PARAMETRI]);
	dbmmclose($dbm);	

	$contenuto.= "<div align=right>";
		
	$contenuto.="<form action='$PHP_SELF' method='post'>";
	$contenuto.="<table border=0 cellpadding=5 cellspacing=0>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>contributo spese postali: </a><td>";
	$contenuto.="<td valign=top  align=left><input NAME='new_acqu[CONTRIBUTOPOSTA]' value='$acqu[CONTRIBUTOPOSTA]' TYPE=Text SIZE='40' MAXLENGTH='80'></td>";
	$contenuto.="</tr>";
	
	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>contributo spese corriere: </a><td>";
	$contenuto.="<td valign=top  align=left><input NAME='new_acqu[CONTRIBUTOCORRIERE]' value='$acqu[CONTRIBUTOCORRIERE]' TYPE=Text SIZE='40' MAXLENGTH='80'></td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>ordine minimo: </a><td>";
	$contenuto.="<td valign=top  align=left><input NAME='new_acqu[ORDINE_MINIMO]' value='$acqu[ORDINE_MINIMO]' TYPE=Text SIZE='40' MAXLENGTH='80'></td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>disponibilita' corriere con contrassegno: </a><td>";
	if($acqu[CONTRASSEGNO_CORRIERE]=="NO"): $selezionato_no="selected"; endif;
	$contenuto.="<td valign=top  align=left><select name='new_acqu[CONTRASSEGNO_CORRIERE]'>
        <option value='SI'>si
        <option value='NO' $selezionato_no>no
    </select>
	</td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>disponibile pagamento su conto corrente bancario: </a><td>";
	if($acqu[BANCA]=="no"): $selezionato_no="selected"; endif;
	$contenuto.="<td valign=top  align=left><select name='new_acqu[BANCA]'>
        <option value='si'>si
        <option value='no' $selezionato_no>no
    </select>
	</td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>coordinate bancarie per eventuale pagamento su conto corrente bancario: </a><td>";
	$contenuto.="<td valign=top  align=left><TEXTAREA NAME='new_acqu[CONTOBANCA]' ROWS='3' COLS='40' WRAP=Virtual>$acqu[CONTOBANCA]</TEXTAREA></td>";
	$contenuto.="</tr>";


	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>disponibile pagamento su conto postale: </a><td>";
	if($acqu[POSTA]=="no"): $selezionato_no="selected"; endif;
	$contenuto.="<td valign=top  align=left><select name='new_acqu[POSTA]'>
        <option value='si'>si
        <option value='no' $selezionato_no>no
    </select>
	</td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>coordinate per eventuale pagamento su conto postale: </a><td>";
	$contenuto.="<td valign=top  align=left><TEXTAREA NAME='new_acqu[CONTOPOSTA]' ROWS='3' COLS='40' WRAP=Virtual>$acqu[CONTOPOSTA]</TEXTAREA></td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>disponibile pagamento con carta di credito (via fax): </a><td>";
	if($acqu[CARTA]=="no"): $selezionato_no="selected"; endif;
	$contenuto.="<td valign=top  align=left><select name='new_acqu[CARTA]'>
        <option value='si'>si
        <option value='no' $selezionato_no>no
    </select>
	</td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right><a class='testo'>indirizzo cui spedire gli ordini: </a><td>";
	$contenuto.="<td valign=top  align=left><TEXTAREA NAME='new_acqu[INDIRIZZO]' ROWS='3' COLS='40' WRAP=Virtual>$acqu[INDIRIZZO]</TEXTAREA></td>";
	$contenuto.="</tr>";

	$contenuto.="<tr>";
	$contenuto.="<td valign=top  align=right>&nbsp;";
		// i parametri ancora da attivare
		$contenuto.="<input NAME='new_acqu[SCONTO_5]' value='$acqu[SCONTO_5]' TYPE=hidden>";
		$contenuto.="<input NAME='new_acqu[SCONTO_10]' value='$acqu[SCONTO_10]' TYPE=hidden>";
		$contenuto.="<input NAME='new_acqu[ACQUISTI_INGROSSO]' value='$acqu[ACQUISTI_INGROSSO]' TYPE=hidden>";
	$contenuto.="</td>";
	$contenuto.="<td valign=top  align=left>";
		$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
		$contenuto.= "<input type='hidden' name='s' value='$s'>";
		$contenuto.= "<input type='hidden' name='az' value='controlla_acqu'>";
		$contenuto.="<INPUT TYPE=Submit VALUE=' invia '></td>";
	$contenuto.="</tr>";

	$contenuto.="</table>";
	$contenuto.= "</form>";
	$contenuto.= "</div>";
	$contenuto.="<br><br>";

	html($contenuto, $messaggio);
endif;

// GESTIONE STIL - EFFETTUO CONTROLLO (prima di immissione) --------------------------
if($az=="aggiorna_font"): //non faccio controlli al momento
	if($IDoggetto==""): $IDoggetto="1"; endif;
	$az="immetti_font"; // quando controllo va bene, faccio immissione
endif;

// GESTIONE STIL - IMMETTO valori  ---------------------------------------------------
if($az=="immetti_font"):

	//ritraduco i dati
	$new_stil[FONT_TITOLO]=$nuovo_font_titolo;
	$new_stil[FONT_TESTO]=$nuovo_font_testo;
	$new_stil[FONT_LISTE]=$nuovo_font_liste;

	// IMMISSIONE DATI
	$dbm = dbmmopen("dati/STIL","w");
		dbmmreplace($dbm, $IDoggetto, serialize($new_stil));  
	dbmmclose($dbm);	
	
	$contenuto.= "<BR><a class='titolo'>valori aggiornati</a><BR><hr>";
	$az="gestione_font"; // cos“ la edita
endif;

// DEFAULT GESTIONE STIL: MODIFICA FONT ---------------------------------------	
if($az=="gestione_font"):

	if($IDoggetto==""): $IDoggetto="1"; endif;

	$contenuto.= "<a class='titolo'>Modifica Font</a>&nbsp;|&nbsp;
	<a href='' onclick=\"window.open('help.php?pg=XXX', '', 'toolbar=no,location=no,directory=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=300' );return false;\">help</a><br>";
	$contenuto.= "<a class='testo'>scegli il font che preferisci e aggiorna l'intero sito premendo 'invia'</a>";
	
	// PRENDO I DATI
	$dbm = dbmmopen("dati/STIL","r");
		$stil=unserialize(dbmmfetch($dbm, $IDoggetto));
		$c_stil=unserialize(dbmmfetch($dbm,"campi"));
		$t_stil=unserialize(dbmmfetch($dbm,"tipi"));
		$n_stil=unserialize(dbmmfetch($dbm,"nomi"));
	dbmmclose($dbm);	

	
	$font_disponibili= array(
		"Arial", 
		"Times New Roman", 
		"Arial Black", 
		"Arial Narrow", 
		"Avant Garde", 
		"Bookman", 
		"Bookman Old Style", 
		"Comic Sans MS", 
		"Courier New", 
		"Garamond", 
		"Helvetica", 
		"Helvetica Narrow", 
		"Impact", 
		"New Century Schoolbook", 
		"Palatino", 
		"Tahoma", 
		"Zapfchancery", 
		"Verdana", 
		"Trebuchet MS"
		);
		
	// SCRIVO IL SELECT PER MODIFICARE IL FONT
	$contenuto.= "<div align=right>";
		
		$font_titolo_attuale=$stil[FONT_TITOLO];
		$font_testo_attuale=$stil[FONT_TESTO];
		$font_liste_attuale=$stil[FONT_LISTE];
				
	$contenuto.="
	<form name='FormFont' action='$PHP_SELF' method='post'>";
	
	// SCRIVO GLI ALTRI DATI
	for ($j = 0; $j <= $c_stil[numero_campi]; $j++):
 		$nome_campo=$c_stil[$j];
		if($nome_campo!="FONT_TITOLO" && $nome_campo!="FONT_TESTO" && $nome_campo!="FONT_LISTE"):  
			$contenuto.= "<input NAME='new_stil[$nome_campo]' value='$stil[$nome_campo]' TYPE=hidden>"; 
		endif;
	endfor;

	$contenuto.="
	<script language='Javascript'>
	function updateFontTitolo() {
		var nIndex=eval('document.FormFont.nuovo_font_titolo');
		document.all.fonttitolo.style.fontFamily=nIndex.value;
		document.all.fonttitolo.innerHTML=nIndex.value;
		}

	function updateFontTesto() {
		var nIndex=eval('document.FormFont.nuovo_font_testo');
		document.all.fonttesto.style.fontFamily=nIndex.value;
		document.all.fonttesto.innerHTML=nIndex.value;
		}
	
	function updateFontListe() {
		var nIndex=eval('document.FormFont.nuovo_font_liste');
		document.all.fontliste.style.fontFamily=nIndex.value;
		document.all.fontliste.innerHTML=nIndex.value;
		}
	</script>

	<style type='text/css'>
	<!--
	A.testo {color: #$stil[COLORE_TESTO]; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
	A.titolo {color: #$stil[COLORE_TITOLO]; text-decoration:bold; font-family: $stil[FONT_TITOLO]; font-weight: bold; font-size: $dimensione_titolo}
	A.liste {color: #$stil[COLORE_LISTE]; text-decoration:none; font-family: $stil[FONT_LISTE]; font-weight: bold; font-size: $dimensione_liste}
	-->
	</style>";

	$contenuto.="
		<table border=0 cellpadding=5 cellspacing=0 align=left width=300>
		<tr><td colspan=2 bgcolor='CCCCCC'><Font face='arial' size=3><b>Scegli il font</b></font></td></td></tr>";

	$contenuto.="
		  <tr>
		    <th align=right bgcolor='EEEEEE'><font face='arial' size='2' color='#000000' >Font Titoli:</font></th>
		    <td bgcolor='EEEEEE'>
		      <select name='nuovo_font_titolo' onChange='updateFontTitolo()'>";
				reset($font_disponibili);
				while ( list( $key, $val ) = each( $font_disponibili ) ):
					$selezionato="";
					if($stil[FONT_TITOLO]=="$val"): $selezionato="selected"; endif;
			        	$contenuto.="<option value='$val' $selezionato><font face='$val'>$val</font>";
				endwhile;
				$contenuto.="
		      </select>
		    </td>
		  </tr>
			";

	$contenuto.="
		  <tr>
		    <th align=right bgcolor='EEEEEE'><font face='arial' size='2' color='#000000' >Font Testo:</font></th>
		    <td bgcolor='EEEEEE'>
		      <select name='nuovo_font_testo' onChange='updateFontTesto()'>";
				reset($font_disponibili);
				while ( list( $key, $val ) = each( $font_disponibili ) ):
					$selezionato="";
					if($stil[FONT_TESTO]=="$val"): $selezionato="selected"; endif;
			        	$contenuto.="<option value='$val' $selezionato><font face='$val'>$val</font>";
				endwhile;
				$contenuto.="
		      </select>
		    </td>
		  </tr>
			";

	$contenuto.="
		  <tr>
		    <th align=right bgcolor='EEEEEE'><font face='arial' size='2' color='#000000' >Font Liste:</font></th>
		    <td bgcolor='EEEEEE'>
		      <select name='nuovo_font_liste' onChange='updateFontListe()'>";
				reset($font_disponibili);
				while ( list( $key, $val ) = each( $font_disponibili ) ):
					$selezionato="";
					if($stil[FONT_LISTE]=="$val"): $selezionato="selected"; endif;
			        	$contenuto.="<option value='$val' $selezionato><font face='$val'>$val</font>";
				endwhile;
				$contenuto.="
		      </select>
		    </td>
		  </tr>
			";
		
	$contenuto.= "	
	<tr><td colspan=2 bgcolor='CCCCCC'><Font face='arial' size=3><b>Preview</b></font></td></td></tr>
	<tr><td align=right valign=top bgcolor='EEEEEE'><font face='arial' size='2' color='#000000'><b>Titoli:</b></font></td><td valign=top bgcolor='EEEEEE'><div id=fonttitolo>$font_titolo_attuale</div></td></tr>
	<tr><td align=right valign=top bgcolor='EEEEEE'><font face='arial' size='2' color='#000000'><b>Testo:</b></font></td><td valign=top bgcolor='EEEEEE'><div id=fonttesto>$font_testo_attuale</div></td></tr>
	<tr><td align=right valign=top bgcolor='EEEEEE'><font face='arial' size='2' color='#000000'><b>Liste:</b></font></td><td valign=top bgcolor='EEEEEE'><div id=fontliste>$font_liste_attuale</div></td></tr>
	";

	$contenuto.= "	
	<tr>
		<td align=right bgcolor='CCCCCC'>&nbsp;</td><td align=right bgcolor='CCCCCC'>";
		$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
		$contenuto.= "<input type='hidden' name='s' value='$s'>";
		$contenuto.= "<input type='hidden' name='az' value='aggiorna_font'>";
		$contenuto.= "<INPUT TYPE=Submit VALUE=' invia '>";
		$contenuto.= "
		</td>
  	</tr>
	</table>";
	$contenuto.= "</form>";
	$contenuto.= "</div>";
		
	html($contenuto, $messaggio);
endif;

// GESTIONE NAVI - EFFETTUO CONTROLLO (prima di immissione) --------------------------
if($az=="controlla_navi"): 
		
	// metto in ordine quello che arriva in $new stemperando i doppi
	asort($new);
	$j=1;
	while ( list( $key, $val ) = each( $new ) ):
		if($val!="0"):
			$brandnew[$key]=$j;
			$j++;
		else: // se vale 0 o se Ž nullo
			$brandnew[$key]="0";		
		endif;
	endwhile;	
	
	$az="immetti_navi"; // quando controllo va bene, faccio immissione
endif;

// GESTIONE NAVI - IMMETTO valori  ---------------------------------------------------
if($az=="immetti_navi"):

	// Preparo l'array delle cambiate
	$dbm = dbmmopen("dati/NAVI","w");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$ullo=dbmmfirstkey($dbm);
		$contatore=dbmmfetch($dbm, "contatore");
		while($ullo!=""):
			$nav=unserialize(dbmmfetch($dbm, $ullo));	
			for($i = 1; $i<=$contatore; $i++):
				if($brandnew[$i]!="" && $i==$nav[IDoggetto]):	
					$cambiate[$nav[IDoggetto]]=$brandnew[$i];
				endif;
			endfor;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;

		// immetto i valori (non posso farlo dentro al ciclo sopra perchŽ l'ordine dei record del dbm cambia dopo ogni replace)
		while ( list( $key, $val ) = each( $cambiate ) ):
			$nav=unserialize(dbmmfetch($dbm, $key));	
			$nav[POSIZIONE_MENU]=$val;
			dbmmreplace($dbm, $nav[IDoggetto], serialize($nav));  
		endwhile;
	dbmmclose($dbm);	
	
	$contenuto.= "<BR><a class='titolo'>valori aggiornati</a><BR><hr>";
	$az="gestione_menu"; // cos“ la edita
endif;

// DEFAULT GESTIONE NAVI amministratore: elenco & ordine moduli ---------------------------------------	
if($az=="gestione_menu"):

	$contenuto.= "<hr><a class='titolo'>Moduli attivati (Modifica ordine men&ugrave;)</a>&nbsp;|&nbsp;
	<a href='' onclick=\"window.open('help.php?pg=XXX', '', 'toolbar=no,location=no,directory=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=300' );return false;\">help</a><br>";

	$contenuto.= "<div align=left>";
	$contenuto.= "<blockquote>";
	$contenuto.= "<table><tr><td align=right>";
	$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";		
	
	// PRENDO I DATI e li metto in $elenco
	$dbm = dbmmopen("dati/NAVI","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$ullo=dbmmfirstkey($dbm);
		while($ullo!=""):
			if($ullo!="campi" && $ullo!="tipi" && $ullo!="nomi" && $ullo!="contatore"):
				$nav=unserialize(dbmmfetch($dbm, $ullo));		
					$elenco_nomi[$nav[IDoggetto]]="<a class='testo'>$nav[LINK_MENU]</a>";
					if ($nav[POSIZIONE_MENU]>0):		//assegno 100 alla chiave se $nav[POSIZIONE_MENU] vale 0
						$temp=$nav[POSIZIONE_MENU];	//cosi posso poi ordinare le chiavi per valore
					else:
						$temp=100;
					endif;
					$elenco_ordine[$nav[IDoggetto]]="$temp";
			endif;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;
	dbmmclose($dbm);	

	// ORDINO I MODULI in base al valore che hanno in $elenco
	asort($elenco_ordine); // li ordino per quello che Ž in valore di $elenco

	// CONTO I MODULI
	$moduli_attivabili=count($elenco_ordine);
	$moduli_attivi=0;
	reset($elenco_ordine);
	while ( list( $key, $val ) = each( $elenco_ordine ) ):
		if($val>0 && $val!=100): $moduli_attivi++; endif;
	endwhile;
 
	// MOSTRO I MODULI
	reset($elenco_ordine);
	while ( list( $key, $val ) = each( $elenco_ordine ) ):
		//if($val>0):
			$contenuto.= "$elenco_nomi[$key]: <SELECT NAME='new[$key]' SIZE=1>";
			for($p=0; $p<=$moduli_attivabili; $p++):
				$selezionato="";
				if($val==$p): $selezionato="selected"; endif;
				$contenuto.= "<OPTION value='$p' $selezionato>$p";
			endfor;
			$contenuto.= "</SELECT><br>";
		//endif;
	endwhile;

	$contenuto.= "<br>";
	$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto.= "<input type='hidden' name='s' value='$s'>";
	$contenuto.= "<input type='hidden' name='az' value='controlla_navi'>";
	$contenuto.= "<input type='submit' value=' Invia '>";
	$contenuto.= "</form>";
	$contenuto.= "</td></tr></table>";
	$contenuto.= "</blockquote>";
	$contenuto.= "</div>";

	html($contenuto, $messaggio);

endif;

// FINE PANNELLI AUSILIARI  ----------------------------------------------------------------------
	
// DEFAULT ----------------------------------------------------------------------------------------
if($az==""):
	$contenuto.= "<a class='titolo'>Modifica dati</a>";

	$contenuto.= "<div align=left>";
	$contenuto.= "<blockquote>";
	$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=IDEK'>identikit</a><br><br>";
	$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=NAVI'>moduli attivati e menu'</a> - ";
		$contenuto.= "(<a href='$PHP_SELF?s=$s&gs=$gs&az=gestione_menu'>solo l'ordine dei menu</a>)<br><br>";
	$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=PAGI'>pagine base (index, chi, dove, prodotti)</a><br><br>";
	$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=STIL'>stile del sito</a> - ";
		$contenuto.= "(<a href='$PHP_SELF?s=$s&gs=$gs&az=gestione_font'>solo i font</a>)<br><br>";

	$contenuto.= "<a href='ricerche.php?s=$s&gs=$gs&az=aggiorna'>upload file classifiche</a><br><br>";
		
	//$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=CATA'>catalogo</a><br><br>";
	$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=lista&dbmusato=UTEN'>utenti</a><br><br>";
	//$contenuto.= "<a href='$PHP_SELF?s=$s&gs=$gs&az=gestione_acqu'>parametri per l'acquisto</a><br><br>";
	$contenuto.= "</blockquote>";
	$contenuto.= "</div>";

	html($contenuto, $messaggio);
endif;

// INIZIALIZZAZIONE ----------------------------------------------------------------------
// controllo se esiste $dbmusato.db 
if(!file_exists("dati/$nomedbm") && !file_exists("dati/$nomedbm.db")):
	if($gs!=""):
 		include "catalogo_inizializzazione.incl";
	else:
		$contenuto.="<a class='testo'>Il $nomedbm in questo momento e' vuoto</a>";
		html($contenuto, $messaggio);
	endif;
endif;


// VISUALIZZO SINGOLO IDOGGETTO $dbmusato ---------------------------------------------
if($az=="singolo"):
// mostra i valori di $IDoggetto
// arriva:
//			az - singolo
//			dbmusato - il nome del dbm utilizzato
//			IDoggetto - l'oggetto da visualizzare
// utilizza:
//			catalogo_singolo_p.incl - per visualizzazione pubblica

	$contenuto.= "<a class='titolo'><b>Visualizzazione dati singolo oggetto in $dbmusato</b></a><br><br>";
	// PRENDO I DATI
	$dbm = dbmmopen("dati/$dbmusato","r");
		$cata=unserialize(dbmmfetch($dbm,"$IDoggetto"));
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
	dbmmclose($dbm);	

		$contenuto.= "<div align=right>";	
		for ($j = 1; $j <= $c_cata[numero_campi]; $j++):
 			$nome_campo=$c_cata[$j];
			if($t_cata[$nome_campo]=="t"):  $contenuto.= "<B>$n_cata[$nome_campo]</B>: $cata[$nome_campo]<br>"; endif;
			if($t_cata[$nome_campo]=="b"):  $contenuto.= "<B>$n_cata[$nome_campo]</B>: $cata[$nome_campo]<br>"; endif;
			srand((double)microtime()*1000000); $randval = rand(); // per ricaricare la fotografia
			if($t_cata[$nome_campo]=="f"):  $contenuto.= "<img src='$cata[$nome_campo]?$randval'><br>"; endif;
		endfor;
		$contenuto.= "</div>";

	html($contenuto, $messaggio);
endif;	

// ELENCO $dbmusato -----------------------------------------------------------
if($az=="lista"):
// elenca i record di $dbmusato
// arriva:
//			az - vuoto
//			gs - potrebbe avere un valore
//			dbmusato - il nome del dbm utilizzato
// utilizza:
//			catalogo_lista_p.incl - per visualizzazione pubblica dell'elenco
//			catalogo_lista_g.incl - per visualizzazione al gestore dell'elenco

	$contenuto.= "<a class='titolo'><b>Gestione di $dbmusato</b></a><br><br>";
	if($gs!=""):
		$contenuto.= "<hr>
			<a href='$PHP_SELF?gs=$gs&s=$s&az=nuovo&dbmusato=$dbmusato'>nuovo oggetto</a>
			<hr>";
	endif;
				
	// PRENDO I DATI e li metto in prendo_cata
	// con chiave l'intero array e valore il $campo_per_ordinare
	if($campo_per_ordinare==""): $campo_per_ordinare="IDoggetto"; endif; // il default
	$dbm = dbmmopen("dati/$dbmusato","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$ullo=dbmmfirstkey($dbm);
		while($ullo!=""):
			if($ullo!="campi" && $ullo!="tipi" && $ullo!="nomi" && $ullo!="contatore"):
				$valore=dbmmfetch($dbm, $ullo);
				$cata=unserialize($valore);
				$prendo_cata[$valore]=$cata[$campo_per_ordinare];
			endif;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;
	dbmmclose($dbm);	

	// ORDINO I DATI in base al valore che hanno in $prendo_cata
	asort($prendo_cata); // li ordino per quello che Ž in valore di $prendo_cata

	// visualizzazione brutos elenco
	// PRENDO E MOSTRO I DATI
/*
	//Visualizzazione vecchia,pesca i dati dal dbm,e non dall'array prendo_cata ordinato
	$elencati="0";
	$dbm = dbmmopen("dati/$dbmusato","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$ullo=dbmmfirstkey($dbm);
		while($ullo!=""):
			if($ullo!="campi" && $ullo!="tipi" && $ullo!="nomi" && $ullo!="contatore"):
				$cata=unserialize(dbmmfetch($dbm, $ullo));
								
				$contenuto.= "<a class='testo'>$cata[IDoggetto]</a> - ";
				$nome01=$c_cata[1]; $contenuto.= "<a class='testo'>$cata[$nome01]</a> - ";
				$nome02=$c_cata[2]; $contenuto.= "<a class='testo'>$cata[$nome02] - </a>";
				$nome03=$c_cata[3]; $contenuto.= "<a class='testo'>$cata[$nome03]</a><br>";
				$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az=modifica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>modifica</a> | <a href='$PHP_SELF?gs=$gs&s=$s&az=eliminaoggetto&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>elimina</a> | <a href='$PHP_SELF?gs=$gs&s=$s&az=duplica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>duplica</a><br><br>";
				$contenuto.= "<hr>";
				
				$elencati++;
			endif;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;
	dbmmclose($dbm);	
*/
	$elencati="0";
	$dbm = dbmmopen("dati/$dbmusato","r");
	reset($prendo_cata);
	$c_cata=unserialize(dbmmfetch($dbm,"campi"));
	$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
	$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
	while ( list( $key, $val ) = each( $prendo_cata ) ):
		$cata=unserialize(dbmmfetch($dbm, $val));
		$contenuto.= "<a class='testo'>$cata[IDoggetto]</a> - ";
		$nome01=$c_cata[1]; $contenuto.= "<a class='testo'>$cata[$nome01]</a> - ";
		$nome02=$c_cata[2]; $contenuto.= "<a class='testo'>$cata[$nome02] - </a>";
		$nome03=$c_cata[3]; $contenuto.= "<a class='testo'>$cata[$nome03]</a><br>";
		$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az=modifica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>modifica</a> | <a href='$PHP_SELF?gs=$gs&s=$s&az=eliminaoggetto&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>elimina</a> | <a href='$PHP_SELF?gs=$gs&s=$s&az=duplica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>duplica</a><br><br>";
		$contenuto.= "<hr>";
		$elencati++;
	endwhile;
	dbmmclose($dbm);	

	if($elencati==""):
		$contenuto.="<a class='testo'>In questo momento il catalogo e' vuoto</a>";
	endif;

	html($contenuto, $messaggio);
endif;
?>