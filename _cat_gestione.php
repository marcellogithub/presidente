<?php  
	require "_funzioni.incl";
	require "_cat_azioni.incl";
	
 	set_time_limit(180);

	if($dbmusato==""): $dbmusato="CATA"; endif; //il dbmusato di default
	
	$gs="admin";// qui lavora solo l'admin

// AGGIORNAMENTO upload & replace  ----------------------------------------------
if( ( $az=="aggiorna" && $gs!="" ) || ( $az=="aggiornaA" && $gs!="" ) ): 
	include "_cat_aggiornamento.incl"; 	
endif;

// VISUALIZZO SINGOLO IDOGGETTO $dbmusato ---------------------------------------------
if($az=="singolo"):

	$contenuto.= "<span class='titolo'><b>Visualizzazione dati singolo oggetto in $dbmusato</b></span><br><br>";
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
			srand((double)microtime()*1000000); $randval = rand(); // per ricaricare la fotografia
			if($t_cata[$nome_campo]=="f"):  $contenuto.= "<img src='$cata[$nome_campo]?$randval'><br>"; endif;
		endfor;
		$contenuto.= "</div>";

	html($contenuto, $messaggio);
endif;	

// ELENCO $dbmusato -----------------------------------------------------------
if($az=="lista"):

	$contenuto.= "<span class='testo'>Contenuto di <b>$dbmusato</b></span><br>";
	$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az=nuovo&dbmusato=$dbmusato'>nuovo oggetto</a> | ";
	$contenuto.="<a href=\"$PHP_SELF?az=struttura_edita&dbmusato=$dbmusato&gs=$gs&s=$s\">edita tabella</a>";
	$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=600>";
				
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
				$prendo_cata[$valore]=$cata[$campo_per_ordinare];
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
		if($quanti<10): $quanti=10; endif;
		$visti=intval($gia)+1;
		$j=0;
		while ( list( $codice, $val ) = each( $prendo_cata ) ):		
			if( $j>=$gia && $j<($gia+$quanti) && $j<=$oggettitrovatitotale):
				$visti++;
			endif;
			$j++;
		endwhile;
		$visti--; // perche' nell'ultimo giro fa un ++ in piu' 
		if($gia>=$quanti): $precedenti="<span class='testo'><a HREF=$PHP_SELF?$para&az=lista&s=$s&dbmusato=$dbmusato&gs=$gs&quanti=$quanti&gia=".($gia-$quanti).">precedenti</a></span>"; endif;
		if($gia>=$quanti && $visti<$oggettitrovatitotale): $inmezzo="<span class='testo'> | </span>"; endif;
		if($visti<$oggettitrovatitotale): $prossimi="<span class='testo'><a HREF=$PHP_SELF?$para&az=lista&s=$s&dbmusato=$dbmusato&gs=$gs&quanti=$quanti&gia=$visti>prossimi</a></span>"; endif;
	endif;

	// NAVIGAZIONE
	$contenuto.= "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=dddddd><tr>";
	$contenuto.= "<td><span class='testo'><br><br>trovati $oggettitrovatitotale record, visualizzati da ".($gia+1)." a $visti.</span></td>"; 
	$contenuto.= "<td align=right><span class='testo'><br><br>$precedenti $inmezzo $prossimi</span></td>";
	$contenuto.= "</tr></table>";
	$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=600>";

	// MOSTRO I DATI
	$oggettitrovatitotale=count($prendo_cata);
	if($quanti<10): $quanti=10; endif;
	$visti=intval($gia)+1;
	$j=0;
	reset($prendo_cata);
	while ( list( $key, $val ) = each( $prendo_cata ) ):
		if( $j>=$gia && $j<($gia+$quanti) && $j<=$oggettitrovatitotale):
			//$cata=unserialize($key);
			$cata=unserialize(dbmmfetch($dbm, $val));
			$contenuto.= "<br><br>";
			$contenuto.= "<span class='testo'>$cata[IDoggetto]</span> - ";
			for($u=4;$u<=8;$u++):
				$nome=$c_cata[$u]; $cata[$nome]=stripslashes($cata[$nome]); $contenuto.="<span class='testo'>". substr($cata[$nome], 0,100) ."</span> - ";
			endfor;
				
			$contenuto.= "
						<br>
						<a href='$PHP_SELF?gs=$gs&s=$s&az=modifica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>modifica</a> | 
						<a href='$PHP_SELF?gs=$gs&s=$s&az=eliminaoggetto&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>elimina</a> | 
						<a href='$PHP_SELF?gs=$gs&s=$s&az=duplica&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>duplica</a>
						<br>
						";
			$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=600>";
			$elencati++;
			$visti++;
		endif;
		$j++;
	endwhile;
	
	if($elencati==""):
		$contenuto.="<span class='testo'>In questo momento il catalogo e' vuoto</span>";
	endif;

	// NAVIGAZIONE
	$contenuto.= "<table width=100% border=0 cellpadding=0 cellspacing=0 bgcolor=dddddd><tr>";
	$contenuto.= "<td><span class='testo'><br><br>trovati $oggettitrovatitotale record, visualizzati da ".($gia+1)." a $visti.</span></td>"; 
	$contenuto.= "<td align=right><span class='testo'><br><br>$precedenti $inmezzo $prossimi</span></td>";
	$contenuto.= "</tr></table>";
	

	html($contenuto, $messaggio);
endif;

// ELIMINO tabella - ------------------------------
if($az=="tabella_elimina"):
	
	// prima chiedo conferma
	$contenuto.=$intestazione_struttura;
	if($azb!="tabella_elimina_confermato"):
		$contenuto.="<div align=center>";
		$contenuto.="<span class=testo>Sicuro di voler eliminare la tabella <b>$tabella_eliminata</b>?</span><br><br>";
		$contenuto.="
				<a href='$PHP_SELF?az=tabella_elimina&azb=tabella_elimina_confermato&tabella_eliminata=$tabella_eliminata&s=$s&gs=$gs'>S&iacute; la elimino</a>
				 --
				<a href='$PHP_SELF?az=struttura_edita&dbmusato=$tabella_eliminata&s=$s&gs=$gs'> No la tengo</a>
				<br>
				";
		$contenuto.="</div>";
		html($contenuto, $messaggio);	
	endif;
	
	// elimino il file
	unlink("dati/$tabella_eliminata");			
	
	$contenuto.="<span class='titolo'>Eliminata la tabella <b>$tabella_eliminata</b></span><hr>"; 
	
	$az=""; // cos“ va ad elenco tabelle
endif;

// NUOVA TABELLA salva ------------------------------------------------------------
if($az=="tabella_nuova_salva"):

	// verifico che il nome di tabella non sia giˆ usato
	$errore="";
	$handle=opendir('dati');
	while ($file = readdir($handle)):
		if(!is_dir($file)): // ignoro eventuali directory e le due directory per andare su
			if($file==$nome_nuova_tabella): $errore.="il nome <font color=444444>$nome_nuova_tabella</font> e' gia' usato."; endif;
		endif;
	endwhile;
	closedir($handle);			
	if($errore!=""):
		$contenuto.="<span class=titolo>Non si puo' fare</span><br><br>";
		$contenuto.="<span class=testo>$errore</span><br>";
		$contenuto.="<span class=testo>torna indietro</span><br>";
		html($contenuto, $messaggio);			
	endif;
	
	$contenuto.="<span class='testo'>Struttura <b>tabella aggiunta</b></span><br>";
	$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=600>";

	// creo la nuova tabella, le d˜ i permessi adeguati, metto il contatore
	$dbm = dbmmopen("dati/$nome_nuova_tabella","n");
		$contenuto.= "<span class=titolo>creata la tabella $nome_nuova_tabella</span><br>";	
	dbmmclose($dbm);
	
	// d˜ i permessi adeguati
	if(chmod("dati/$NOME_DBMM", 0755)): 		
		$contenuto.="<span class=testo>cambiati i permessi di $nome_nuova_tabella</span><br><br>"; 
	else: 
		$contenuto.="<span class=testo><font color=red>NON cambiati i permessi di $nome_nuova_tabella</font></span><br><br>"; 
	endif;

	// metto i valori di base
	// nome campi interno
		$c_dbmm=array();
		$c_dbmm[]="IDoggetto";
		$c_dbmm[]="DATA_creazione";
		$c_dbmm[]="DATA_modifica";
		$c_dbmm[numero_campi]=count($c_dbmm);
	// nome campi esterno
		$n_dbmm=array();
		$n_dbmm[IDoggetto]="IDoggetto";
		$n_dbmm[DATA_creazione]="DATA_creazione";
		$n_dbmm[DATA_modifica]="DATA_modifica";
	// tipo campi
		$t_dbmm=array();
		$t_dbmm[IDoggetto]="t";
		$t_dbmm[DATA_creazione]="Dc";
		$t_dbmm[DATA_modifica]="D";
		
	// inserisco i valori di base
	$dbm = dbmmopen("dati/$nome_nuova_tabella","w");
		dbmmreplace($dbm,"campi", serialize($c_dbmm));  
		dbmmreplace($dbm,"nomi", serialize($n_dbmm));  
		dbmmreplace($dbm,"tipi", serialize($t_dbmm));  
	dbmmclose($dbm);

	$dbmusato=$nome_nuova_tabella;
	$az="struttura_edita";
		
endif;

// NUOVA TABELLA  form creazione ------------------------------------------------------------
if($az=="tabella_nuova"):
	$contenuto.="<span class='testo'>Struttura <b>Aggiungi tabella</b></span><br>";
	$contenuto.= "<img src=\"imma/DOTnero50.gif\" height=1 width=600>";
	$contenuto.= "<br>";
	$contenuto.= "<br>";

	$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$contenuto.="<table>";
	$contenuto.="<tr>
				<td><span class='testo'><b>nome nuova tabella: </b></span></td>
				<td><input NAME=\"nome_nuova_tabella\" value=\"$nome_nuova_tabella\" TYPE=Text SIZE='20 MAXLENGTH='80'></td>
			</tr>";
			
	$contenuto.= "<tr><td valign=top colspan=2>";
	$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto.= "<input type='hidden' name='s' value='$s'>";
	$contenuto.= "<input type='hidden' name='az' value='tabella_nuova_salva'>";
	$contenuto.= "<input type='submit' value=' Invia '>";
	$contenuto.= "</td></tr>";
	$contenuto.= "</table>";
	$contenuto.= "</form>";
	
	html($contenuto, $messaggio);	
endif;

// MODIFICA SELECT  ---------------------------------------------------------------------
if($az=="modifica_select" || $az=="immetti_select" || $az=="elimina_select"): 
	$contenuto.=$intestazione_struttura;
	include "_cat_select.incl"; 
endif;

// SALVO il dbmusato - --------------------------------------------------------------------
// salvo tutto tranne i valori che hanno procedura a parte
if($az=="struttura_salva"):

	// se sto inserendo nuovo campo
	if($neww_c_cata[__nuovo_campo__]):
		// prendo i dati
		$dbm = dbmmopen("dati/$dbmusato","r");
			$new_c_cata=unserialize(dbmmfetch($dbm,"campi"));
			$new_t_cata=unserialize(dbmmfetch($dbm,"tipi"));
			$new_n_cata=unserialize(dbmmfetch($dbm,"nomi"));
			$new_a_cata=unserialize(dbmmfetch($dbm,"accesso"));
			// i valori saranno aggiunti dopo
		dbmmclose($dbm);
		
		// verifico che non ci siano duplicati di nome e nome esterno
		$errore="";
		while ( list( $key, $val ) = each( $new_c_cata ) ):
			if($neww_c_cata[__nuovo_campo__]==$val): $errore.= "il nome <font color=444444>$neww_c_cata[__nuovo_campo__]</font> e' gia' usato.<br>"; endif;
			if($neww_n_cata[__nuovo_campo__]==$new_n_cata[$val]): $errore.= "il nome esterno <font color=444444>$neww_n_cata[__nuovo_campo__]</font> e' gia' usato.<br>"; endif;
		endwhile;
		if($errore!=""):
			$contenuto.=$intestazione_struttura;
			$contenuto.="<span class=titolo>Non si puo' fare</span><br><br>";
			$contenuto.="<span class=testo>$errore</span><br>";
			$contenuto.="<span class=testo>torna indietro</span><br>";
			html($contenuto, $messaggio);			
		endif;
		
		// costruisco il nuovo campo
		$nome_campo_nuovo=$neww_c_cata[__nuovo_campo__];
		$new_c_cata[]=$nome_campo_nuovo;
		$new_t_cata[$nome_campo_nuovo]=$neww_t_cata[__nuovo_campo__];
		$new_n_cata[$nome_campo_nuovo]=$neww_n_cata[__nuovo_campo__];
		$new_a_cata[$nome_campo_nuovo]=$neww_a_cata[__nuovo_campo__];
		
		$new_c_cata[numero_campi]++;			

		$contenuto.="<span class='titolo'>Campo $nome_campo_nuovo ($new_n_cata[$nome_campo_nuovo]) aggiunto.</span><hr>";

	endif;
	
	//pulisco il new_a_cata
	while ( list( $key, $val ) = each( $new_a_cata ) ):
		$new_a_cata[$key]=trim($val);
	endwhile;
		
	// salvo la struttura della tabella
	$dbm = dbmmopen("dati/$dbmusato","w");
		dbmmreplace($dbm,"campi", serialize($new_c_cata));
		dbmmreplace($dbm,"nomi", serialize($new_n_cata));  
		dbmmreplace($dbm,"tipi", serialize($new_t_cata));  
		dbmmreplace($dbm,"accesso", serialize($new_a_cata));  
	dbmmclose($dbm);
	
	$az="struttura_edita";
endif;

// RINOMINA CAMPO per il dbmusato - ------------------------------
if($az=="struttura_rinomina_campo"):
	
	$contenuto.="
		<span class='titolo'>non si puo' rinominare il campo</span><br>
		<span class='testo'>rinominando il campo si perdono anche i valroi nella tabella, bisogna fare procedura apposita</span><hr>
		";

	$az="struttura_edita";
endif;

// NUOVO CAMPO per il dbmusato - ------------------------------
if($az=="struttura_nuovo_campo"):
	$contenuto.=$intestazione_struttura;

	$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$contenuto.="<table>";
	$contenuto.="<tr>
				<td colspan=5><span class='testo'><b>aggiungi nuovo campo a $dbmusato </b></span></td>
			</tr>";
	$contenuto.="<tr>
				<td>&nbsp;</td>
				<td><span class='testo'><b>nome campo</b></span></td>
				<td><span class='testo'><b>nome esterno</b></span></td>
				<td><span class='testo'><b>tipo</b></span></td>
				<td><span class='testo'><b><font color=444444>valori</font></b></span></td>
			</tr>";
	
	$key="__nuovo_campo__";
	$val="__nuovo_campo__";
	$contenuto.="<tr>";
	$contenuto.="<td>&nbsp;</td>";
	$contenuto.="<td><input NAME=\"neww_c_cata[__nuovo_campo__]\" value=\"$val\" TYPE=Text SIZE='20 MAXLENGTH='80'></td>";
	$contenuto.="<td><input NAME=\"neww_n_cata[__nuovo_campo__]\" value=\"\" TYPE=Text SIZE='20 MAXLENGTH='80'></td>";
		$contenuto.="<td>";				
					// select con accesso al campo
					$opzioni="";
					unset($lista_option);
					$lista_option[]="&nbsp;"; // nulla, gestore vede e scrive -- i campi normali
					$lista_option[]="g"; // il gestore vede solamente -- ad ese DATAmodifica
					$lista_option[]="a"; // il gestore non vede -- ad es IDoggetto
					
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							$opzioni=$opzioni."<option value=\"$lista_option[$k]\" >$lista_option[$k]</option>";
						endif;
					endfor;				
					$contenuto.= "<select NAME=\"new_a_cata[$val]\"> $opzioni</select>"; 
		$contenuto.="</td>";
	$contenuto.="<td>";				
			// select con i tipi del campo						
				$opzioni="";
				unset($lista_option);
				$lista_option[]="t"; // testo
				$lista_option[]="f"; // foto
				$lista_option[]="l"; // file
				$lista_option[]="b"; // box di testo
				$lista_option[]="a"; // array
				$lista_option[]="s"; // select
				$lista_option[]="sc"; // select custom
				$lista_option[]="r"; // radiobutton
				$lista_option[]="c"; // checkbox
				$lista_option[]="d"; // data tre pezzi
				$lista_option[]="D"; // data timestamp
				$lista_option[]="Dc"; // data creazione
				$lista_option[]="re"; // relazionato
				$lista_option[]="rl"; // relativo
				$lista_option[]="ut"; // ultimo utente
				
				for ($k=0;$k<count($lista_option);$k++):
					if(trim($lista_option[$k])!=""):
						$opzioni=$opzioni."<option value=\"$lista_option[$k]\">$lista_option[$k]</option>";
					endif;
				endfor;				
				$contenuto.= "<select NAME=\"neww_t_cata[__nuovo_campo__]\"> $opzioni</select>"; 
	$contenuto.="</td>";
	$contenuto.="<td>&nbsp;</td>";
	$contenuto.="</tr>";
		
	$contenuto.= "<tr><td valign=top colspan=5>";
	$contenuto.= "<input type='hidden' name='dbmusato' value='$dbmusato'>";
	$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto.= "<input type='hidden' name='s' value='$s'>";
	$contenuto.= "<input type='hidden' name='az' value='struttura_salva'>";
	$contenuto.= "<input type='submit' value=' Invia '>";
	$contenuto.= "</td></tr>";
	$contenuto.= "</table>";
	$contenuto.= "</form>";

	html($contenuto, $messaggio);
endif;

// DUPLICA il campo_duplicato - ------------------------------
if($az=="struttura_duplica_campo"):

	$dbm = dbmmopen("dati/$dbmusato","w");
		// prendo i dati
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		$a_cata=unserialize(dbmmfetch($dbm,"accesso"));
		
		// duplico il campo
		$nome_campo_duplicando=$c_cata[$campo_duplicato];
		$nome_campo_duplicato="dup_".$nome_campo_duplicando;
		$c_cata[]="dup_".$nome_campo_duplicando;
		$t_cata[$nome_campo_duplicato]=$t_cata[$nome_campo_duplicando];
		$n_cata[$nome_campo_duplicato]="dup_".$n_cata[$nome_campo_duplicando];
		$v_cata[$nome_campo_duplicato]=$v_cata[$nome_campo_duplicando];
		$a_cata[$nome_campo_duplicato]=$a_cata[$nome_campo_duplicando];
		$c_cata[numero_campi]++;			
			
		// salvo tutto
		dbmmreplace($dbm,"campi", serialize($c_cata));  
		dbmmreplace($dbm,"nomi", serialize($n_cata));  
		dbmmreplace($dbm,"tipi", serialize($t_cata));  
		dbmmreplace($dbm,"valori", serialize($v_cata));  
		dbmmreplace($dbm,"accesso", serialize($a_cata));  
	dbmmclose($dbm);
	
	$nome_campo_duplicato=$c_cata[$campo_duplicato];
	$contenuto.="<span class='titolo'>Duplicato il campo $c_cata[$campo_duplicato] ($n_cata[$nome_campo_duplicato])</span><hr>";
	
	$az="struttura_edita";
endif;

// ELIMINO il campo_eliminato - ------------------------------
if($az=="struttura_elimina_campo"):

	// prendo i dati
	$dbm = dbmmopen("dati/$dbmusato","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		$a_cata=unserialize(dbmmfetch($dbm,"accesso"));
	dbmmclose($dbm);
	
	// prima chiedo conferma
	if($azb!="struttura_elimina_campo_confermato"):
		$contenuto.=$intestazione_struttura;
		$contenuto.="<div align=center>";
		$nome_campo_eliminato=$c_cata[$campo_eliminato];
		$contenuto.="<span class=testo>Sicuro di voler eliminare il campo $c_cata[$campo_eliminato] ($n_cata[$nome_campo_eliminato] )?</span><br><br>";
		$contenuto.="
				<a href='$PHP_SELF?az=struttura_elimina_campo&azb=struttura_elimina_campo_confermato&dbmusato=$dbmusato&campo_eliminato=$campo_eliminato&s=$s&gs=$gs'>S&iacute; lo elimino</a>
				 --
				<a href='$PHP_SELF?az=struttura_edita&dbmusato=$dbmusato&s=$s&gs=$gs'> No lo tengo</a>
				<br>
				";
		$contenuto.="</div>";
		html($contenuto, $messaggio);	
	endif;
	
	$nome_campo_eliminato=$c_cata[$campo_eliminato];
	$contenuto.="
		<span class='titolo'>Eliminato il campo $c_cata[$campo_eliminato] ($n_cata[$nome_campo_eliminato])</span><br>
		<span class='testo'><font color=red>ricordarsi</font> di aggiornare anche i valori dei record<span><hr>
		"; //devo scrivere prima
	
	// salvo tutto tranne il campo eliminato
	$dbm = dbmmopen("dati/$dbmusato","w");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		$a_cata=unserialize(dbmmfetch($dbm,"accesso"));
		
		unset($c_cata[$campo_eliminato]);
		unset($t_cata[$campo_eliminato]);
		unset($n_cata[$campo_eliminato]);
		unset($v_cata[$campo_eliminato]);
		unset($a_cata[$campo_eliminato]);
		
		$c_cata[numero_campi]--;			

		dbmmreplace($dbm,"campi", serialize($c_cata));  
		dbmmreplace($dbm,"nomi", serialize($n_cata));  
		dbmmreplace($dbm,"tipi", serialize($t_cata));  
		dbmmreplace($dbm,"valori", serialize($v_cata));  
		dbmmreplace($dbm,"accesso", serialize($a_cata));  
	dbmmclose($dbm);
	
	
	$az="struttura_edita";
endif;

// INTESTAZIONE  -----------------------------------------------------------------------				
	//per scegliere un db da gestire
	$contenuto.= "<div align=right>";
	$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";

	$contenuto.= "<span class=testo>vai a </span>";
	$contenuto.= "<SELECT NAME='dbmusato' SIZE=1>";
		$handle=opendir('dati');
		while ($file = readdir($handle)):
			if(!is_dir($file)): // ignoro eventuali directory e le due directory per andare su
				$selected=""; if($file==$dbmusato): $selected="SELECTED"; endif;
				$contenuto.= "<OPTION value=\"$file\" $selected>$file";
			endif;
		endwhile;
		closedir($handle);			
	$contenuto.= "</SELECT>";
	$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto.= "<input type='hidden' name='s' value='$s'>";
	$contenuto.= "<input type='hidden' name='az' value='lista'>";
	$contenuto.= "<input type='submit' value=' cambia '>";
	$contenuto.= " <a href='$PHP_SELF?gs=$gs&s=$s&az=tabella_nuova'>nuova</a> | ";
	$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az='>tutte</a>";
	$contenuto.= "</form>";
	$contenuto.= "</div>";

	// intestazione struttura
	$intestazione_struttura.="<span class='testo'>Struttura tabella: <b>$dbmusato</b></span><br>";
	$intestazione_struttura.="<a href=\"$PHP_SELF?az=struttura_edita&dbmusato=$dbmusato&gs=$gs&s=$s\">edita tabella</a> | ";
	$intestazione_struttura.="<a href=\"$PHP_SELF?az=tabella_elimina&tabella_eliminata=$dbmusato&gs=$gs&s=$s\"> elimina tabella</a> | ";
	$intestazione_struttura.="<a href=\"$PHP_SELF?az=lista&dbmusato=$dbmusato&gs=$gs&s=$s\">contenuto tabella</a> | ";
	$intestazione_struttura.="<a href=\"$PHP_SELF?az=struttura_nuovo_campo&dbmusato=$dbmusato&gs=$gs&s=$s\">nuovo campo</a>";
	$intestazione_struttura.="<hr>";

// EDITO il dbmusato - ------------------------------
if($az=="struttura_edita"):
	$contenuto.=$intestazione_struttura;

	$dbm = dbmmopen("dati/$dbmusato","r");
		$c_cata=unserialize(dbmmfetch($dbm,"campi"));
		$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
		$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
		$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		$a_cata=unserialize(dbmmfetch($dbm,"accesso"));
	dbmmclose($dbm);	

	$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$contenuto.="<table>";
	$contenuto.="<tr>
				<td>&nbsp;</td>
				<td><span class='testo'><b>nome campo</b></span></td>
				<td><span class='testo'><b>nome esterno</b></span></td>
				<td><span class='testo'><b>acc</b></span></td>
				<td><span class='testo'><b>tipo</b></span></td>
				<td><span class='testo'><b>valori</b></span></td>
			</tr>";
				
	reset($c_cata);
	while ( list( $key, $val ) = each( $c_cata ) ):
		$contenuto.="<tr>";
		$contenuto.="
					<td><font size=1>
					<a href='$PHP_SELF?az=struttura_duplica_campo&dbmusato=$dbmusato&campo_duplicato=$key&s=$s&gs=$gs'  title='duplica campo'>d</a> 
					| 
					<a href='$PHP_SELF?az=struttura_elimina_campo&dbmusato=$dbmusato&campo_eliminato=$key&s=$s&gs=$gs'  title='elimina campo'>e</a>
					</font></td>
					";
		$contenuto.="<td><input NAME=\"new_c_cata[$key]\" value=\"$val\" TYPE=hidden><a href='$PHP_SELF?az=struttura_rinomina_campo&dbmusato=$dbmusato&campo_rinominato=$key&s=$s&gs=$gs'  title='rinomina campo'>$val</a></td>";
		$contenuto.="<td><input NAME=\"new_n_cata[$val]\" value=\"$n_cata[$val]\" TYPE=Text SIZE='20 MAXLENGTH='80'></td>";
		$contenuto.="<td>";				
					// select con accesso al campo
					$opzioni="";
					unset($lista_option);
					$lista_option[]="&nbsp;"; // nulla, gestore vede e scrive -- i campi normali
					$lista_option[]="g"; // il gestore vede solamente -- ad ese DATAmodifica
					$lista_option[]="a"; // il gestore non vede -- ad es IDoggetto
					
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							if ($lista_option[$k]==$a_cata[$val]):
								$sel="selected";
							else:
								$sel="";
							endif;
							$opzioni=$opzioni."<option value=\"$lista_option[$k]\" $sel>$lista_option[$k]</option>";
						endif;
					endfor;				
					$contenuto.= "<select NAME=\"new_a_cata[$val]\"> $opzioni</select>"; 
		$contenuto.="</td>";
		$contenuto.="<td>";				
					// select con i tipi del campo
					$opzioni="";
					unset($lista_option);
					$lista_option[]="t"; // testo
					$lista_option[]="f"; // foto
					$lista_option[]="l"; // file
					$lista_option[]="b"; // box di testo
					$lista_option[]="a"; // array
					$lista_option[]="s"; // select
					$lista_option[]="sc"; // select custom
					$lista_option[]="r"; // radiobutton
					$lista_option[]="c"; // checkbox
					$lista_option[]="d"; // data tre pezzi
					$lista_option[]="D"; // data timestamp
					$lista_option[]="Dc"; // data creazione
					$lista_option[]="re"; // relazionato
					$lista_option[]="rl"; // relativo
					$lista_option[]="ut"; // ultimo utente
					
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							if (trim($lista_option[$k])==$t_cata[$val]):
								$sel="selected";
							else:
								$sel="";
							endif;
							$opzioni=$opzioni."<option value=\"$lista_option[$k]\" $sel>$lista_option[$k]</option>";
						endif;
					endfor;				
					$contenuto.= "<select NAME=\"new_t_cata[$val]\"> $opzioni</select>"; 
		$contenuto.="</td>";
		$contenuto.="<td>";				
					// visualizzo valori del campo select, select_custom, radiobutton
					if(	$t_cata[$val]=="s" ||
						$t_cata[$val]=="sc" ||
						$t_cata[$val]=="re" ||
						$t_cata[$val]=="rl" ||
						$t_cata[$val]=="r"
						):
						
						$opzioni="";
						$lista_option=explode("::",$v_cata[$val]);
						for ($k=0;$k<count($lista_option);$k++):
							if(trim($lista_option[$k])!=""):
								$opzioni=$opzioni."<option value=\"$lista_option[$k]\" $sel>$lista_option[$k]</option>";
							endif;
						endfor;				
						$contenuto.= "
						 	<select NAME=\"new_v_cata[$val]\"> $opzioni</select>
							<a href='$PHP_SELF?az=modifica_select&dbmusato=$dbmusato&nome_campo=$val&s=$s&gs=$gs' title='modifica valori'>m</a> 
							"; 
					endif;						
		$contenuto.="</td>";
		$contenuto.="</tr>";
		
	endwhile;
	$contenuto.= "<tr><td valign=top colspan=5>";
	$contenuto.= "<input type='hidden' name='dbmusato' value='$dbmusato'>";
	$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto.= "<input type='hidden' name='s' value='$s'>";
	$contenuto.= "<input type='hidden' name='az' value='struttura_salva'>";
	$contenuto.= "<input type='submit' value=' Invia '>";
	$contenuto.= "</td></tr>";
	$contenuto.= "</table>";
	$contenuto.= "</form>";

	html($contenuto, $messaggio);
endif;


// DEFAULT elenco tabelle --------------------
	
	$contenuto.="<table>";
	$contenuto.="<tr><td colspan=2><span class=testo><b>Elenco tabelle</b></span><br><img src=\"imma/DOTnero50.gif\" height=1 width=600></td></tr>";
	$handle=opendir('dati');
	while ($file = readdir($handle)):
		if(!is_dir($file)): // ignoro eventuali directory e le due directory per andare su
			$link_gestore="<a href=\"$PHP_SELF?az=struttura_edita&dbmusato=$file&gs=$gs&s=$s\">edita</a> | ";
			$link_gestore.="<a href=\"$PHP_SELF?az=tabella_elimina&tabella_eliminata=$file$gs&s=$s\">elimina</a> | ";
			$link_gestore.="<a href=\"$PHP_SELF?az=lista&dbmusato=$file&gs=$gs&s=$s\">contenuto</a>";
			$contenuto.= "<tr><td><span class=testo>$file</span></td><td>$link_gestore</tr>";
		endif;
	endwhile;
	closedir($handle);	
	$contenuto.="</table>";
				
	html($contenuto, $messaggio);

?>