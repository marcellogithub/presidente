<?php  
// effettua le inizializzazioni di tutti i db e delle directory utilizzati 

include "_funzioni.incl";

// INIZIALIZZAZIONE DIRECTORY -------------------------------------------------------
if(!is_dir("dati")): mkdir("dati", 0777); endif;
if(!is_dir("imma")): mkdir("imma", 0777); endif;
if(!is_dir("file")): mkdir("file", 0777); endif;

// INIZIALIZZAZIONE SESS ------------------------------------------------------------
// session, con anche i dati del carrello
if (!file_exists("dati/SESS.db")): 
	$dbm = dbmmopen("dati/SESS","n");
	dbmmclose($dbm);
	chmod("dati/SESS.db", 0777);
	// struttura di SESS:
	// $sess[s] - sessione
	// $sess[time] - ultimo accesso (timestamp)
	// $sess[permessi] - le lettere, copiate da $ute[permessi]
	// $sess[$carrello] - l'array $carrello serializzato
	$messaggio.= "creato il SESS.db<br>";
endif;

// INIZIALIZZAZIONE ASPIRANTI -------------------------------------------------------------
// le ASPIRANTI per il gioco
if(!file_exists("dati/ASPIRANTI.db")):
	
	//nome campi interno
	$c_aspiranti[0]="IDoggetto";
	$c_aspiranti[1]="data";
	$c_aspiranti[2]="nome";
	$c_aspiranti[3]="email";
	$c_aspiranti[4]="titolo";
	$c_aspiranti[5]="login";
	$c_aspiranti[6]="psw";
	$c_aspiranti[7]="attuale_denaro";
	$c_aspiranti[8]="attuale_popolarita";
	$c_aspiranti[9]="attuale_potere";
	$c_aspiranti[10]="attuale_prestigio";
	$c_aspiranti[11]="car_astuzia";
	$c_aspiranti[12]="car_capacita";
	$c_aspiranti[13]="car_carisma";
	$c_aspiranti[14]="car_competenza";
	$c_aspiranti[15]="car_conoscenze";
	$c_aspiranti[16]="car_fortuna";
	$c_aspiranti[17]="car_intelligenza";
	$c_aspiranti[18]="car_salute";
	$c_aspiranti[19]="car_senso";
	$c_aspiranti[20]="car_spregiudicatezza";
	$c_aspiranti[21]="numero_mosse";
	$c_aspiranti[numero_campi]="22"; // serve per fare i cicli for

	// nome campi esterno
	$n_aspiranti[IDoggetto]="IDoggetto";
	$n_aspiranti[data]="data";
	$n_aspiranti[nome]="nome";
	$n_aspiranti[email]="email";
	$n_aspiranti[titolo]="titolo";
	$n_aspiranti[login]="login";
	$n_aspiranti[psw]="psw";
	$n_aspiranti[attuale_denaro]="attuale_denaro";
	$n_aspiranti[attuale_popolarita]="attuale_popolarita";
	$n_aspiranti[attuale_potere]="attuale_potere";
	$n_aspiranti[attuale_prestigio]="attuale_prestigio";
	$n_aspiranti[car_astuzia]="car_astuzia";
	$n_aspiranti[car_capacita]="car_capacita";
	$n_aspiranti[car_carisma]="car_carisma";
	$n_aspiranti[car_competenza]="car_competenza";
	$n_aspiranti[car_conoscenze]="car_conoscenze";
	$n_aspiranti[car_fortuna]="car_fortuna";
	$n_aspiranti[car_intelligenza]="car_intelligenza";
	$n_aspiranti[car_salute]="car_salute";
	$n_aspiranti[car_senso]="car_senso";
	$n_aspiranti[car_spregiudicatezza]="car_spregiudicatezza";
	$n_aspiranti[numero_mosse]="numero_mosse";

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_aspiranti[IDoggetto]="t";
	$t_aspiranti[data]="t";
	$t_aspiranti[nome]="t";
	$t_aspiranti[email]="t";
	$t_aspiranti[titolo]="t";
	$t_aspiranti[login]="t";
	$t_aspiranti[psw]="t";
	$t_aspiranti[attuale_denaro]="t";
	$t_aspiranti[attuale_popolarita]="t";
	$t_aspiranti[attuale_potere]="t";
	$t_aspiranti[attuale_prestigio]="t";
	$t_aspiranti[car_astuzia]="t";
	$t_aspiranti[car_capacita]="t";
	$t_aspiranti[car_carisma]="t";
	$t_aspiranti[car_competenza]="t";
	$t_aspiranti[car_conoscenze]="t";
	$t_aspiranti[car_fortuna]="t";
	$t_aspiranti[car_intelligenza]="t";
	$t_aspiranti[car_salute]="t";
	$t_aspiranti[car_senso]="t";
	$t_aspiranti[car_spregiudicatezza]="t";
	$t_aspiranti[numero_mosse]="t";

	// inizializzazione valori  --------------------------------------------------
	$aspiranti00[IDoggetto]="1";
	$aspiranti00[data]="0";
	$aspiranti00[nome]="marcello";
	$aspiranti00[email]="3478196937@libero.it";
	$aspiranti00[titolo]="la prima partita";
	$aspiranti00[login]="marcello";
	$aspiranti00[psw]="6347";
	$aspiranti00[attuale_denaro]="3";
	$aspiranti00[attuale_popolarita]="3";
	$aspiranti00[attuale_potere]="2";
	$aspiranti00[attuale_prestigio]="2";
	$aspiranti00[car_astuzia]="si";
	$aspiranti00[car_capacita]="si";
	$aspiranti00[car_carisma]="si";
	$aspiranti00[car_competenza]="si";
	$aspiranti00[car_conoscenze]="si";
	$aspiranti00[car_fortuna]="no";
	$aspiranti00[car_intelligenza]="no";
	$aspiranti00[car_salute]="no";
	$aspiranti00[car_senso]="no";
	$aspiranti00[car_spregiudicatezza]="no";
	$aspiranti00[numero_mosse]="0";

	$dbm = dbmmopen("dati/ASPIRANTI","n");
		dbmmreplace($dbm,"nomi", serialize($n_aspiranti));  
		dbmmreplace($dbm,"tipi", serialize($t_aspiranti));  
		dbmmreplace($dbm,"campi", serialize($c_aspiranti));  
		dbmmreplace($dbm, $aspiranti01[IDoggetto], serialize($aspiranti00));  
	  dbmmreplace($dbm,"contatore", "$i");  // contiene il numero progressivo di IDoggetto in ASPIRANTI
	dbmmclose($dbm);
	
	$contenuto.= "creato ASPIRANTI.db,<br>";
endif;

// INIZIALIZZAZIONE PRESIDENTE -------------------------------------------------------------
// le PRESIDENTE per il gioco
if(!file_exists("dati/PRESIDENTE.db")):
	
	//nome campi interno
	$c_presidente[0]="IDoggetto";
	$c_presidente[1]="titolo";
	$c_presidente[2]="sottotitolo";
	$c_presidente[3]="link";
	$c_presidente[4]="testo01";
	$c_presidente[5]="foto01";
	$c_presidente[6]="variazione_denaro";
	$c_presidente[7]="variazione_popolarita";
	$c_presidente[8]="variazione_potere";
	$c_presidente[9]="variazione_prestigio";
	$c_presidente[numero_campi]="10"; // serve per fare i cicli for

	// nome campi esterno
	$n_presidente[IDoggetto]="IDoggetto";
	$n_presidente[titolo]="titolo";
	$n_presidente[sottotitolo]="sottotitolo";
	$n_presidente[link]="link";
	$n_presidente[testo01]="testo 01";
	$n_presidente[foto01]="foto 01";
	$n_presidente[variazione_denaro]="variazione_denaro";
	$n_presidente[variazione_popolarita]="variazione_popolarita";
	$n_presidente[variazione_potere]="variazione_potere";
	$n_presidente[variazione_prestigio]="variazione_prestigio";

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_presidente[IDoggetto]="t";
	$t_presidente[titolo]="t";
	$t_presidente[sottotitolo]="t";
	$t_presidente[link]="t";
	$t_presidente[testo01]="b";
	$t_presidente[foto01]="f";
	$t_presidente[variazione_denaro]="t";
	$t_presidente[variazione_popolarita]="t";
	$t_presidente[variazione_potere]="t";
	$t_presidente[variazione_prestigio]="t";

	// inizializzazione valori  --------------------------------------------------
	
	// metto tutto dentro all'array 	
	$arrayintero=file("testocorto.txt");
	
	// utilizzo i miei indicatori di fine record <ff>
	$arraystring="";
	while ( list( $key, $val ) = each( $arrayintero ) ):
		$arraystring.=$val;
	endwhile;
	$array=explode("<ff>", $arraystring);
	
	// trasferisco il testo da $array al magazzino, con codice interno come key
	reset($array); // porto il pointer all'inizio

	$dbm = dbmmopen("dati/PRESIDENTE","n");
		dbmmreplace($dbm,"nomi", serialize($n_presidente));  
		dbmmreplace($dbm,"tipi", serialize($t_presidente));  
		dbmmreplace($dbm,"campi", serialize($c_presidente));  
		$i=1;
		$righetotali=count($array);
		while($i<$righetotali):
			$entry=$array[$i];	
			
			$ddddd=explode("^", $entry);			
			$vuoto=$ddddd[0]; //per assicurarmi di non avere CR o LF
			$presidente01[IDoggetto]=$ddddd[1];
			$presidente01[titolo]="";
			$presidente01[sottotitolo]="";
			$presidente01[link]="";
			$presidente01[testo01]="$ddddd[2]";
			$presidente01[foto01]=" ";
			$presidente01[variazione_denaro]=$ddddd[3];
			$presidente01[variazione_popolarita]=$ddddd[4];
			$presidente01[variazione_potere]=$ddddd[5];
			$presidente01[variazione_prestigio]=$ddddd[6];
		
			dbmmreplace($dbm, $presidente01[IDoggetto], serialize($presidente01));  
			
			$i++;		
		endwhile;
		dbmmreplace($dbm,"contatore", "$i");  // contiene il numero progressivo di IDoggetto in PRESIDENTE
		$contenuto.="<br>Aggiornamento effettuato. In totale " . $i . " record.</font>";
	dbmmclose($dbm);
	
	// fine inizializzazione valori  --------------------------------------------------

	$contenuto.= "creato PRESIDENTE.db,<br>";
endif;

// INIZIALIZZAZIONE PAGI -------------------------------------------------------------
// le pagine fisse, a partire da index, chi siamo, dove siamo, prodotti e servizi
if(!file_exists("dati/PAGI.db")):
	
	//nome campi interno
	$c_pagi[0]="IDoggetto";
	$c_pagi[1]="titolo";
	$c_pagi[2]="sottotitolo";
	$c_pagi[3]="link";
	$c_pagi[4]="testo01";
	$c_pagi[5]="foto01";
	$c_pagi[6]="testo02";
	$c_pagi[7]="foto02";
	$c_pagi[8]="testo03";
	$c_pagi[9]="foto03";
	$c_pagi[numero_campi]="10"; // serve per fare i cicli for

	// nome campi esterno
	$n_pagi[IDoggetto]="IDoggetto";
	$n_pagi[titolo]="index";
	$n_pagi[sottotitolo]="pagina iniziale del sito";
	$n_pagi[link]="index";
	$n_pagi[testo01]="testo 01";
	$n_pagi[foto01]="foto 01";
	$n_pagi[testo02]="testo 02";
	$n_pagi[foto02]="foto 02";
	$n_pagi[testo03]="testo 03";
	$n_pagi[foto03]="foto 03";

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_pagi[IDoggetto]="t";
	$t_pagi[titolo]="t";
	$t_pagi[sottotitolo]="t";
	$t_pagi[link]="t";
	$t_pagi[testo01]="b";
	$t_pagi[foto01]="f";
	$t_pagi[testo02]="b";
	$t_pagi[foto02]="f";
	$t_pagi[testo03]="b";
	$t_pagi[foto03]="f";

	// inizializzazione valore 1
	$pagi01[IDoggetto]="1";
	$pagi01[titolo]="Nome del sito";
	$pagi01[sottotitolo]="slogan del sito";
	$pagi01[link]="index";
	$pagi01[testo01]="qui si trova la prima pagina del sito";
	$pagi01[foto01]=" ";
	$pagi01[testo02]=" ";
	$pagi01[foto02]=" ";
	$pagi01[testo03]=" ";
	$pagi01[foto03]=" ";

	$dbm = dbmmopen("dati/PAGI","n");
		dbmmreplace($dbm,"nomi", serialize($n_pagi));  
		dbmmreplace($dbm,"tipi", serialize($t_pagi));  
		dbmmreplace($dbm,"campi", serialize($c_pagi));  
		dbmmreplace($dbm,"1", serialize($pagi01));  
		dbmmreplace($dbm,"contatore", "1");  // contiene il numero progressivo di IDoggetto in PAGI
	dbmmclose($dbm);

	$contenuto.= "creato PAGI.db, con index, chisiamo, dovesiamo, prodottieservizi<br>";
endif;

// INIZIALIZZAZIONE UTEN ---------------------------------------------------------
// qui ci stanno i dati dell’utente, comprese login, password e privilegi di accesso
if(!file_exists("dati/UTEN.db")):
	
	//nome campi interno
	$c_uten[0]="IDoggetto";
	$c_uten[1]="login";
	$c_uten[2]="password";
	$c_uten[3]="permessi";
	$c_uten[4]="domanda";
	$c_uten[5]="risposta";
	$c_uten[6]="nome";
	$c_uten[7]="indirizzo";
	$c_uten[8]="citta";
	$c_uten[9]="cap";
	$c_uten[10]="provincia";
	$c_uten[11]="tel";
	$c_uten[12]="fax";
	$c_uten[13]="cell";
	$c_uten[14]="email";
	$c_uten[15]="servizi";
	$c_uten[numero_campi]="16"; // serve per fare i cicli for, pari al numero dei c_uten numerati

	// nome campi esterno
	$n_uten[IDoggetto]="ID";
	$n_uten[login]="login";
	$n_uten[password]="password";
	$n_uten[permessi]="permessi";
	$n_uten[domanda]="domanda";
	$n_uten[risposta]="risposta";
	$n_uten[nome]="nome";
	$n_uten[indirizzo]="indirizzo";
	$n_uten[citta]="citta";
	$n_uten[cap]="cap";
	$n_uten[provincia]="provincia";
	$n_uten[tel]="tel";

	$n_uten[fax]="fax";
	$n_uten[cell]="cell";
	$n_uten[email]="email"; 
	$n_uten[servizi]="servizi"; 

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_uten[IDoggetto]="t";
	$t_uten[login]="t";
	$t_uten[password]="t";
	$t_uten[permessi]="t";
	$t_uten[domanda]="t";
	$t_uten[risposta]="t";
	$t_uten[nome]="t";
	$t_uten[indirizzo]="t";
	$t_uten[citta]="t";
	$t_uten[cap]="t";
	$t_uten[provincia]="t";
	$t_uten[tel]="t";
	$t_uten[fax]="t";
	$t_uten[cell]="t";
	$t_uten[email]="t"; 
	$t_uten[servizi]="t";  // in realtà é un array

	// inizializzazione valore
	$utenA[IDoggetto]="1";
	$utenA[login]="admin";
	$utenA[password]="6347";
	$utenA[permessi]="ABCDEFGHKJILMNOPQURSTUVWXYZabcdefghkjilmnopqurstuvwxyz";
	$utenA[domanda]="telefona a Marcello";
	$utenA[risposta]="3478196937";
	$utenA[nome]="amministratore";
	$utenA[indirizzo]="viale Cairoli 127";
	$utenA[citta]="Treviso";
	$utenA[cap]="31100";
	$utenA[provincia]="TV";
	$utenA[tel]="0422 234363";
	$utenA[fax]="0422 234363";
	$utenA[cell]="347 8196937";
	$utenA[email]="3478196937@libero.it"; 
	$utenA[servizi]=serialize($servizi);

	// inizializzazione valore
	$utenB[IDoggetto]="2";
	$utenB[login]="cliente";
	$utenB[password]="cliente";
	$utenB[permessi]="Aa";
	$utenB[domanda]="domanda";
	$utenB[risposta]="risposta";
	$utenB[nome]="nome cliente";
	$utenB[indirizzo]="indirizzo cliente";
	$utenB[citta]="città cliente";
	$utenB[cap]="cap cliente";
	$utenB[provincia]="TV";
	$utenB[tel]="tel cliente";
	$utenB[fax]="fax cliente";
	$utenB[cell]="cell cliente";
	$utenB[email]="email cliente"; 
	$utenB[servizi]=serialize($servizi);

	$dbm = dbmmopen("dati/UTEN","n");
		dbmmreplace($dbm,"nomi", serialize($n_uten));  
		dbmmreplace($dbm,"tipi", serialize($t_uten));  
		dbmmreplace($dbm,"campi", serialize($c_uten));  
		dbmmreplace($dbm, 1, serialize($utenA));  
		dbmmreplace($dbm, 2, serialize($utenB));  
		dbmmreplace($dbm,"contatore", "2");  
	dbmmclose($dbm);

	$messaggio.="creato il db UTEN.db, e immessi il primi utenti admin e cliente<br>";
endif;

// INIZIALIZZAZIONE STIL ---------------------------------------------------------
// qui ci stanno idati relativi al ccs e altri valori complessivi
if(!file_exists("dati/STIL.db")):
	
	//nome campi interno
	$c_stil[0]="IDoggetto";
	$c_stil[1]="NOME_STILE";
	$c_stil[2]="FONT_TITOLO";
	$c_stil[3]="FONT_TESTO";
	$c_stil[4]="FONT_LISTE";
	$c_stil[5]="DIMENSIONE_TITOLO";
	$c_stil[6]="DIMENSIONE_TESTO";
	$c_stil[7]="DIMENSIONE_LISTE";
	$c_stil[8]="COLORE_TITOLO";
	$c_stil[9]="COLORE_TESTO";
	$c_stil[10]="COLORE_LISTE";
	$c_stil[11]="COLORE_SFONDO_A";
	$c_stil[12]="COLORE_SFONDO_B";
	$c_stil[13]="COLORE_SFONDO_C";
	$c_stil[14]="COLORE_SFONDO_D";
	$c_stil[15]="IMMAGINE_SFONDO_A";
	$c_stil[16]="IMMAGINE_SFONDO_B";
	$c_stil[17]="IMMAGINE_SFONDO_C";
	$c_stil[18]="IMMAGINE_SFONDO_D";
	$c_stil[numero_campi]="19"; // serve per fare i cicli for, pari al numero dei c_stil numerati

	// nome campi esterno
	$n_stil[IDoggetto]="IDoggetto";
	$n_stil[NOME_STILE]="nome";
	$n_stil[FONT_TESTO]="font del testo";
	$n_stil[FONT_TITOLO]="font del testo";
	$n_stil[FONT_LISTE]="font liste";
	$n_stil[DIMENSIONE_TITOLO]="dimensione titoli";
	$n_stil[DIMENSIONE_TESTO]="dimensione testo";
	$n_stil[DIMENSIONE_LISTE]="dimensione liste";
	$n_stil[COLORE_TITOLO]="colore titoli";
	$n_stil[COLORE_TESTO]="colore del testo";
	$n_stil[COLORE_LISTE]="colore delle liste";
	$n_stil[COLORE_SFONDO_A]="colore di sfondo A";
	$n_stil[COLORE_SFONDO_B]="colore di sfondo B";
	$n_stil[COLORE_SFONDO_C]="colore di sfondo C";
	$n_stil[COLORE_SFONDO_D]="colore di sfondo D";
	$n_stil[IMMAGINE_SFONDO_A]="immagine di sfondo A";
	$n_stil[IMMAGINE_SFONDO_B]="immagine di sfondo B";
	$n_stil[IMMAGINE_SFONDO_C]="immagine di sfondo C";
	$n_stil[IMMAGINE_SFONDO_D]="immagine di sfondo D";

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_stil[IDoggetto]="t";
	$t_stil[NOME_STILE]="t";
	$t_stil[FONT_TESTO]="t";
	$t_stil[FONT_TITOLO]="t";
	$t_stil[FONT_LISTE]="t";
	$t_stil[DIMENSIONE_TITOLO]="t";
	$t_stil[DIMENSIONE_TESTO]="t";
	$t_stil[DIMENSIONE_LISTE]="t";
	$t_stil[COLORE_TITOLO]="t";
	$t_stil[COLORE_TESTO]="t";
	$t_stil[COLORE_LISTE]="t";
	$t_stil[COLORE_SFONDO_A]="t";
	$t_stil[COLORE_SFONDO_B]="t";
	$t_stil[COLORE_SFONDO_C]="t";
	$t_stil[COLORE_SFONDO_D]="t";
	$t_stil[IMMAGINE_SFONDO_A]="f";
	$t_stil[IMMAGINE_SFONDO_B]="f";
	$t_stil[IMMAGINE_SFONDO_C]="f";
	$t_stil[IMMAGINE_SFONDO_D]="f";
	
	// inizializzazione valore
	$stil[IDoggetto]="1";
	$stil[NOME_STILE]="dicembre2001";
	$stil[FONT_TESTO]="Arial";
	$stil[FONT_TITOLO]="Impact";
	$stil[FONT_LISTE]="arial";
	$stil[DIMENSIONE_TITOLO]="14";
	$stil[DIMENSIONE_TESTO]="10";
	$stil[DIMENSIONE_LISTE]="12";
	$stil[COLORE_TITOLO]="003399";
	$stil[COLORE_TESTO]="000000";
	$stil[COLORE_LISTE]="003300";
	$stil[COLORE_SFONDO_A]="FFFFCC";
	$stil[COLORE_SFONDO_B]="FF9933";
	$stil[COLORE_SFONDO_C]="FFCC00";
	$stil[COLORE_SFONDO_D]="FFF1B1";
	$stil[IMMAGINE_SFONDO_A]="";
	$stil[IMMAGINE_SFONDO_B]="";
	$stil[IMMAGINE_SFONDO_C]="";
	$stil[IMMAGINE_SFONDO_D]="";

	$dbm = dbmmopen("dati/STIL","n");
		dbmmreplace($dbm,"nomi", serialize($n_stil));  
		dbmmreplace($dbm,"tipi", serialize($t_stil));  
		dbmmreplace($dbm,"campi", serialize($c_stil));  
		dbmmreplace($dbm,"1", serialize($stil));  
		dbmmreplace($dbm,"contatore", "1");  
	dbmmclose($dbm);

	$messaggio.="creato il db STIL.db, e immesso il primo stile <I>dicembre2001</I><br>";
endif;

// INIZIALIZZAZIONE IDEK ---------------------------------------------------------
// qui ci sono i dati del sito
if(!file_exists("dati/IDEK.db")):
	
	//nome campi interno
	$c_idek[0]="IDoggetto";
	$c_idek[1]="TITOLOSITO";
	$c_idek[2]="NOMESITO";
	$c_idek[3]="INDIRIZZO";
	$c_idek[4]="TELEFONOSITO";
	$c_idek[5]="FAXSITO";
	$c_idek[6]="EMAILSITO";
	$c_idek[7]="URLSITO";
	$c_idek[8]="FIRMASITO";
	$c_idek[9]="DESCRIZIONESITO";
	$c_idek[10]="DESCRIZIONEBREVESITO";
	$c_idek[11]="PAROLECHIAVESITO";
	$c_idek[12]="STILESITO";
	$c_idek[numero_campi]="13"; // serve per fare i cicli for, pari al numero dei c_idek numerati

	// nome campi esterno
	$n_idek[IDoggetto]="IDoggetto";
	$n_idek[TITOLOSITO]="Titolo sul browser";
	$n_idek[NOMESITO]="Nome del sito";
	$n_idek[INDIRIZZO]="Indirizzo";
	$n_idek[TELEFONOSITO]="Telefono";
	$n_idek[FAXSITO]="fax";
	$n_idek[EMAILSITO]="email";
	$n_idek[URLSITO]="url";
	$n_idek[FIRMASITO]="firma nelle email";
	$n_idek[DESCRIZIONESITO]="descrizione lunga";
	$n_idek[DESCRIZIONEBREVESITO]="descrizione breve";
	$n_idek[PAROLECHIAVESITO]="parole chiave";
	$n_idek[STILESITO]="stile";

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_idek[IDoggetto]="t";
	$t_idek[TITOLOSITO]="t";
	$t_idek[NOMESITO]="t";
	$t_idek[INDIRIZZO]="t";
	$t_idek[TELEFONOSITO]="t";
	$t_idek[FAXSITO]="t";
	$t_idek[EMAILSITO]="t";
	$t_idek[URLSITO]="t";
	$t_idek[FIRMASITO]="b";
	$t_idek[DESCRIZIONESITO]="b";
	$t_idek[DESCRIZIONEBREVESITO]="b";
	$t_idek[PAROLECHIAVESITO]="b";
	$t_idek[STILESITO]="t";

	// inizializzazione valore
	$idek[IDoggetto]="1";
	$idek[TITOLOSITO]="marcello balbo";
	$idek[NOMESITO]="marcello balbo";
	$idek[INDIRIZZO]="via Dante 3, Zero Branco";
	$idek[TELEFONOSITO]="347 819696937";
	$idek[FAXSITO]="fax";
	$idek[EMAILSITO]="3478196937@libero.it";
	$idek[URLSITO]="www.balbo.net";
	$idek[FIRMASITO]="\n-----------------------\n        $idek[NOMESITO] \n  $idek[URLSITO]  \n-----------------------";
	$idek[DESCRIZIONESITO]="descrizione del sito";
	$idek[DESCRIZIONEBREVESITO]="breve descrizione del sito";
	$idek[PAROLECHIAVESITO]="parole chiave per descrivere il sito, divise da virgole";
	$idek[STILESITO]="1";

	$dbm = dbmmopen("dati/IDEK","n");
		dbmmreplace($dbm,"nomi", serialize($n_idek));  
		dbmmreplace($dbm,"tipi", serialize($t_idek));  
		dbmmreplace($dbm,"campi", serialize($c_idek));  
		dbmmreplace($dbm,"1", serialize($idek));  // per ora abbiamo un solo record qui dentro
	dbmmclose($dbm); 

	$messaggio.="creato il db IDEK.db, e immessi i valori fasulli<br>";
endif;

// INIZIALIZZAZIONE NAVI ---------------------------------------------------
// qui c'é la lista dei moduli attivabili, quelli attivi hanno valore >0
if(!file_exists("dati/NAVI.db")):
	
	//nome campi interno
	$c_navi[0]="IDoggetto";
	$c_navi[1]="NOME_MODULO";
	$c_navi[2]="POSIZIONE_MENU";
	$c_navi[3]="LINK_MENU"; // il nome che viene visualizzato sulla barra dei menu
	$c_navi[4]="LINK_FILE"; // l'effettivo link
	$c_navi[6]="BOTTONE_SU"; // eventuale bottone
	$c_navi[7]="BOTTONE_GIU"; // eventuale bottone se ha rollover
	$c_navi[8]="PARAMETRI"; // array con i parametri del modulo
	$c_navi[numero_campi]="9"; // serve per fare i cicli for, pari al numero dei c_navi numerati

	// nome campi esterno 
	$n_navi[IDoggetto]="IDoggetto";
	$n_navi[NOME_MODULO]="NOME_MODULO";
	$n_navi[POSIZIONE_MENU]="POSIZIONE_MENU";
	$n_navi[LINK_MENU]="LINK_MENU"; // il nome che viene visualizzato sulla barra dei menu
	$n_navi[LINK_FILE]="LINK_FILE"; // l'effettivo link
	$n_navi[BOTTONE_SU]="BOTTONE_SU"; // eventuale bottone
	$n_navi[BOTTONE_GIU]="BOTTONE_GIU"; // eventuale bottone se ha rollover
	$n_navi[PARAMETRI]="PARAMETRI"; // array con i parametri del modulo

	// tipo campi
	// t - testo
	// f - foto
	// b - box di testo
	$t_navi[IDoggetto]="t";
	$t_navi[NOME_MODULO]="t";
	$t_navi[POSIZIONE_MENU]="t";
	$t_navi[LINK_MENU]="t"; // il nome che viene visualizzato sulla barra dei menu
	$t_navi[LINK_FILE]="t"; // l'effettivo link
	$t_navi[BOTTONE_SU]="f"; // eventuale bottone
	$t_navi[BOTTONE_GIU]="f"; // eventuale bottone se ha rollover
	$t_navi[PARAMETRI]="t"; // array con i parametri del modulo

	// inizializzazione valori
	$naviA[IDoggetto]="1";
	$naviA[NOME_MODULO]="index";
	$naviA[POSIZIONE_MENU]="1";
	$naviA[LINK_MENU]="index"; // il nome che viene visualizzato sulla barra dei menu
	$naviA[LINK_FILE]="index.php"; // l'effettivo link
	$naviA[BOTTONE_SU]=""; // eventuale bottone
	$naviA[BOTTONE_GIU]=""; // eventuale bottone se ha rollover
	$naviA[PARAMETRI]=""; // array con i parametri del modulo
	$messaggio.= "attivato il modulo index<br>";

	$dbm = dbmmopen("dati/NAVI","n");
		dbmmreplace($dbm,"nomi", serialize($n_navi));  
		dbmmreplace($dbm, "tipi", serialize($t_navi));
		dbmmreplace($dbm, "campi", serialize($c_navi));
		dbmmreplace($dbm,"1", serialize($naviA));  
		dbmmreplace($dbm,"contatore", "1");  
	dbmmclose($dbm);

	$messaggio.= "creato il db NAVI.db,<br>";
endif;

html($contenuto, $messaggio);

?>