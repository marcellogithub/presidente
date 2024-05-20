<?php  
	include "_funzioni.incl";
	
 	set_time_limit(180);

	$NOME_DBMM="PARTITE"; // le partite in corso

// INIZIALIZZO DBMM - ------------------------------
if($az=="inizializzazione"):

	// CANCELLO il vecchio $NOME_DBMM --------------------------------
	if($azb=="cancella"):
		// sto cancellando il vecchio dbm o inizializzandone uno nuovo
		if(!file_exists("dati/$NOME_DBMM")):
			$contenuto.= "non c'era alcun vecchio db $NOME_DBMM<br>";	
		else:	
			if(unlink("dati/$NOME_DBMM")): 
				$contenuto.="cancellato il vecchio db $NOME_DBMM<br>"; 
			else: 
				$contenuto.="NON cancellato il vecchio db $NOME_DBMM<br>"; 
			endif;
		endif;
		$dbm = dbmmopen("dati/$NOME_DBMM","n");
			$contenuto.= "creato il db $NOME_DBMM<br>";	
		dbmmclose($dbm);
		if(chmod("dati/$NOME_DBMM", 0755)): 
			$contenuto.="cambiati i permessi di $NOME_DBMM<br>"; 
		else: 
			$contenuto.="NON cambiati i permessi di $NOME_DBMM<br>"; 
		endif;
		$contatore=0;
	else:
		// sto solo riscrivendo gli header, devo mantenere contatore
		// trovo il IDoggetto massimo e lo userò come contatore
		$dbm = dbmmopen("dati/$NOME_DBMM","n");
			$contatore_attuale=dbmmfetch($dbm,"contatore");
			$ID_max="";
			$ullo=dbmmfirstkey($dbm);
			while($ullo!=""):
				$valore=dbmmfetch($dbm, $ullo);
				$cata=unserialize($valore);
				if(($cata[IDoggetto]*1)>"$ID_max"): $ID_max=$cata[IDoggetto]; endif;
				$ullo=dbmmnextkey($dbm,$ullo);
			endwhile;
		dbmmclose($dbm);
		$contatore=$ID_max;
	endif;
		
	// tipi campo
	/*
	t - testo
	f - foto
	b - box di testo
	(a - array)
	s -- select
	sc -- select customizzabile
	r -- radiobutton
	-- da fare: rc -- radiobutton customizzabile
	c -- checkbox, vale "on" quando il checkbox è selezionato
	l -- file
	d -- data, in tre pezzi immessa dall'utente
	D -- data automatica (serve per sapere l'ultima modifica)
	Dc -- data creazione, automatica
	
	ut= ultimo utente, gestito autonomamente, metto ultimo gs
	
	 re = campo relazionato
		restituisce un select con tutte le ID che rientrano nei paramteri, mostrando il campo indicato 
		in valori ci sono:
			il dbm relazionato
			il campo del dbm da ricercare
			l'operatore per la relazione
			il parametro
			il campo da mostrare
			
			ad esempio --  NOMI::agente::uguale::on::ragione_sociale
				cerca sulla tabella NOMI i record che hanno in "agente" il valore "on" e mostra nel select la ragione sociale
	-- da fare: un re che viene selezionato con popup separato, che permette anche sottosgruppi (per selezioni che possono avere moltissimi elementi, tipo la tabella dei comuni)
	*/
	
	// SCHEMA DEFINIZIONE CAMPI  -----------------------------------
	// $c_dbmm[] -- nome campi interno
	// $d_dbmm[$nome_campo] -- descrizione campo
	// $n_dbmm[$nome_campo] -- nome campi esterno
	// $t_dbmm[$nome_campo] -- tipo campi
	// $v_dbmm[$nome_campo] -- valori campi
	// da fare: $a_dbmm[$nome_campo] -- accesso ai campi

	// CAMPI COMUNI ------------------------------------------------
	$nome_campo="IDoggetto";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="identificativo univoco";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";
	
	$nome_campo="DATA_creazione";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la data della creazione del record";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="Dc";
	
	$nome_campo="DATA_modifica";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la data della ultima modifica sul record";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="D";

	$nome_campo="ultimo_utente";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la ID dell'utente che ha fatto l'ultima modifica sul record";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="ut";
		
	// CAMPI DELLA TABELLA  ----------------------------------------
	$nome_campo="data_inizio";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="data di inizio della partita";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="d";

	$nome_campo="IDutente";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la ID del giocatore";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="login";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la ID del giocatore";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="password";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la ID del giocatore";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="email";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="la ID del giocatore";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";
	
	$nome_campo="titolo";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="il nome dato a questa partita";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="numero_mosse";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="sequenza_mosse";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="le mosse divise da *";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="denaro";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="popolarita";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="potere";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="prestigio";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="t";

	$nome_campo="car_astuzia";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_capacita";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_carisma";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_competenza";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_conoscenze";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_fortuna";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_intelligenza";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_salute";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_senso";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	$nome_campo="car_spregiudicatezza";
	$c_dbmm[]="$nome_campo";
	$d_dbmm[$nome_campo]="";
	$n_dbmm[$nome_campo]=ermeg_replace("_", " ", "$nome_campo");
	$t_dbmm[$nome_campo]="c";

	// CAMPI DI SERVIZIO  ---------------------------------------------
	$c_dbmm[numero_campi]=count($c_dbmm); // serve per fare i cicli for, pari al numero dei c_dbmm numerati
	// bisogna salvarli altrove, altrimenti troppe eccezioni quando sono in modifica dei valori del record
	//$c_dbmm[accesso_tabella]=""; // permessi per vedere, modificare, aggiungere e togliere i record alla tabella
	//$c_dbmm[descrizione_tabella]=""; 
	//$c_dbmm[schema_tabella]=""; 
	
	// salvo tutto ----------------------------------------------------
	$dbm = dbmmopen("dati/$NOME_DBMM","n");
		dbmmreplace($dbm,"nomi", serialize($n_dbmm));  
		dbmmreplace($dbm,"tipi", serialize($t_dbmm));  
		dbmmreplace($dbm,"campi", serialize($c_dbmm));  
		dbmmreplace($dbm,"valori", serialize($v_dbmm));  
		dbmmreplace($dbm,"accesso", serialize($a_dbmm));  
		dbmmreplace($dbm,"contatore", $contatore);  
	dbmmclose($dbm);

	$contenuto.="inserite le definizioni di campo per $NOME_DBMM<hr>";	
endif;
	
// DEFAULT --------------------
	
	$contenuto.="<br>";
	$contenuto.="<hr>";
	$contenuto.="<a href=\"$PHP_SELF?az=inizializzazione&azb=cancella\"> inizializzazione $NOME_DBMM</a><br><br>";
	$contenuto.="<a href=\"$PHP_SELF?az=inizializzazione&\"> cambia $NOME_DBMM</a><br><br>";
	$contenuto.="<div align=right><a href=\"_cat_gestione.php\"> gestione tabelle</a></div><br><br>";

	html($contenuto, $messaggio);


?>