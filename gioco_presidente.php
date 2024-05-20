<?php   
// INDEX

// database usato: PRESIDENTE e PARTITE
// DA FARE: verifica che login sia unica quando immessa nuova partita
// da fare: possiblità di dire "salva partita" e inviare la situazione, richiedere email solo in quel momento
include_once "_start.php";

	include "_funzioni.incl";

	/*
	// testata
	$contenuto.= "<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='100%' >";
	$contenuto.= "<tr><td align=center bgcolor='#CC99CC'>";
	$contenuto.= "<table BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH='100%' bgcolor=336600>";
	$contenuto.= "<tr>";
	$contenuto.= "<td align=left>";
	$contenuto.= "<font color=ffffff size=6 face='impact, verdana'>Il Presidente del consiglio sei tu</font>";
	$contenuto.= "</td></tr>";
	$contenuto.= "</table>";
	$contenuto.= "</td>";
	$contenuto.= "</tr></table>";
	*/

if($az=="" && $IDpartita==""): // è appena arrivato, ci vuole pagina di presentazone
	//per adesso gli faccio cominicare una nnuova partita
	$az="nuova_partita";
endif;

// correggo un bug -----------------------------------------------------------------
if($az=="salva_nota"): 

	$dbm = dbmmopen("dati/PRESIDENTE","w");
		$new_cata=unserialize(dbmmfetch($dbm,$IDoggetto));
		$new_cata[testo01]=$nuovo_testo01;
		$new_cata[variazione_denaro]=$nuova_variazione_denaro;
		$new_cata[variazione_popolarita]=$nuova_variazione_popolarita;
		$new_cata[variazione_potere]=$nuova_variazione_potere;
		$new_cata[variazione_prestigio]=$nuova_variazione_prestigio;
		dbmmreplace($dbm, $IDoggetto, serialize($new_cata));  
	dbmmclose($dbm);
	$allerta.="<span class=testo><font color=blue>Modificato $IDoggetto<br></font></span>";
	
endif;

// segnala un bug -----------------------------------------------------------------
if($az=="segnala"): 

	$dbm = dbmmopen("dati/PARTITE","r");
		$cata=unserialize(dbmmfetch($dbm,$IDpartita));
	dbmmclose($dbm);

	$contenuto.="<span class=titolo>Grazie</span><br><span class=testo>per questa segnalazione.<br><br>Dopo aver inviato tornerai alla partita.<br><br></span>";

		$table_segnala.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
		$table_segnala.= "<table border='0' width=100%>";
		$table_segnala.= "<tr><td valign=top><span class=testo>Scrivi cosa non funzionava: </td></tr>";
		$table_segnala.= "<tr><td valign=top><textarea NAME=\"testo\" rows=\"5\" cols=\"50\"></textarea></span> </td></tr>";
	
		$table_segnala.= "<td valign=top>";
		$table_segnala.= "<input type='hidden' name='login' value='$cata[login]'>"; // per sapere chi la manda
		$table_segnala.= "<input type='hidden' name='email' value='$cata[email]'>"; // per sapere chi la manda
		$table_segnala.= "<input type='hidden' name='IDoggetto' value='$IDoggetto'>";
		$table_segnala.= "<input type='hidden' name='IDpartita' value='$IDpartita'>";
		$table_segnala.= "<input type='hidden' name='s' value='$s'>";
		$table_segnala.= "<input type='hidden' name='gs' value='$gs'>";
		$table_segnala.= "<input type='hidden' name='az' value='ricevi_segnalazione'>";
		$table_segnala.= "<input type='submit' value=' Invia'>";
		$table_segnala.= "</td></tr>";
		$table_segnala.= "</table>";
		$table_segnala.= "</form>";
		$table_segnala.= "<br>";

	$contenuto.=$table_segnala;
	html($contenuto, $messaggio);
endif;

// ricevi un bug -----------------------------------------------------------------
if($az=="ricevi_segnalazione"): 

	$allerta.="<span class=testo><font color=red>Grazie per questa segnalazione,<br> adesso</font></span> ";

	$testo_mail="segnalazione bug di Il Presidente del Consiglio sei TU\n\t\n\t
		da parte di: $login\n\t
		email: $email\n\t
		fatta il: ".date("d-m-y")."\n\t
		IDpartita: $IDpartita\n\t
		pagina su PRESIDENTE: $IDoggetto\n\t
		link:  http://www.edilnol.com/o/presidente/gioco_presidente.php?IDpartita=1&IDoggetto=$IDoggetto&gs=admin\n\t
		\n\t
		testo:\n\t
		$testo\n\t
		\n\t
		\n\t
		";

	$testo_mail=ermeg_replace("	", "", $testo_mail);
	mail("marcello@balbo.net", "bug su PRESIDENTE", $testo_mail, "From: $email");		
endif;

// trova vecchia partita -----------------------------------------------------------------
if($az=="trova_vecchia_partita"): 
	$dbm = dbmmopen("dati/PARTITE","r");
		$cata=unserialize(dbmmfetch($dbm,$IDpartita));

		$ullo=dbmmfirstkey($dbm);
		while($ullo!=""):
			if($ullo!="campi" && $ullo!="tipi" && $ullo!="nomi" && $ullo!="contatore" && $ullo!="valori" && $ullo!="numero_campi"):
				$valore=dbmmfetch($dbm, $ullo);
				$cata=unserialize($valore);
				if($cata[login]==$login && $cata[password]==$psw):
					$trovato=1;
					$IDpartita=$ullo;
					// trovo l'ultima nota in cui era stato
					// le mosse si trovano in $cata[sequenza_mosse] separate da *
					$mosse=explode("*", strrev($cata[sequenza_mosse]));
					$ultima_nota=strrev($mosse[0]);
					$IDoggetto=$ultima_nota;
					$az=""; // così va a riaprirla
					break;
				endif;
			endif;
			$ullo=dbmmnextkey($dbm,$ullo);
		endwhile;
		if($trovato==""):
			$contenuto.="<span class=titolo>Spiacente</span><br><span class=testo>non ho trovato nessuna partita</span>";
			$az="nuova_partita";
			//html($contenuto, $messaggio);
		endif;
	dbmmclose($dbm);
endif;

// nuova partita -----------------------------------------------------------------
if($az=="cerca_vecchia_partita"): 

	$dbm = dbmmopen("dati/PARTITE","r");
		$cata=unserialize(dbmmfetch($dbm,$IDpartita));
	dbmmclose($dbm);

	$table_login.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$table_login.= "<table border='0' bgcolor=CCCCCC><tr><td align=center>";
	$table_login.= "<table border='0'>";
	$table_login.= "<tr><td valign=top colspan=3><span class=testo>Ritrova&nbsp;la&nbsp;tua&nbsp;partita: </td></tr>";
	$table_login.= "<tr>";
	$table_login.= "</td><td valign=top><input NAME=\"login\" value=\"login\" TYPE=Text SIZE='10' MAXLENGTH='30'>";
	$table_login.= "</td><td valign=top><input NAME=\"psw\" value=\"psw\" TYPE=Text SIZE='10' MAXLENGTH='30'></span></td>";

	$table_login.= "<td valign=top>";
	$table_login.= "<input type='hidden' name='dbmusato' value='PARTITE'>";
	$table_login.= "<input type='hidden' name='s' value='$s'>";
	$table_login.= "<input type='hidden' name='gs' value='$gs'>";
	$table_login.= "<input type='hidden' name='az' value='trova_vecchia_partita'>";
	$table_login.= "<input type='submit' value=' Invia'>";
	$table_login.= "</td></tr>";
	$table_login.= "</table>";
	$table_login.= "</td></tr></table>";
	$table_login.= "</form>";
	$table_login.= "<br>";

	$contenuto_testo.= "<div align=left>";
	$contenuto_testo.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$contenuto_testo.= "<table border='0' width=80%>";
	$contenuto_testo.= "<img src=\"imma/DOTtras.gif\" width=1 height=400 align=left>";
	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=titolo>Continua partita</span></td></tr>";
	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=testo>
					Inserisci la login e password che hai utilizzato per cominciare la partita e premi \"invia\": sarai riportato all'ultimo paragrafo letto.<br>
					(la login e password sono state spedite all'inizio della partita all'indirizzo email che avevi indicato).<br><br><br>
					</span></td></tr>";
	$contenuto_testo.= "<tr><td valign=top colspan=2>$table_login</td></tr>";
	$contenuto_testo.= "</div>";

	$contenuto.=$contenuto_testo;
	html($contenuto, $messaggio);

endif;

// nuova partita -----------------------------------------------------------------
if($az=="nuova_partita"): 

	$dbm = dbmmopen("dati/PARTITE","r");
		$cata=unserialize(dbmmfetch($dbm,$IDpartita));
	dbmmclose($dbm);
	
	$contenuto_testo.= "<div align=left>";
	$contenuto_testo.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
	$contenuto_testo.= "<table border='0' width=100%>";

	$contenuto_testo.= "<tr><td valign=top colspan=3><span class=titolo>Nuova partita</span><hr></td></tr>";

		$colonnina_risorse.= "<table border='0'>";
		$colonnina_risorse.= "<tr><td valign=top colspan=2><span class=titoletto>Distribuisci 15 punti tra queste caratteristiche:<br></span></td></tr>";
		$colonnina_risorse.= "<tr><td valign=top>denaro : </td><td valign=top><input NAME=\"new_cata[denaro]\" value=\"4\" TYPE=Text SIZE='3 MAXLENGTH='3'></td></tr>";
		$colonnina_risorse.= "<tr><td valign=top>popolarita' : </td><td valign=top><input NAME=\"new_cata[popolarita]\" value=\"4\" TYPE=Text SIZE='3 MAXLENGTH='3'></td></tr>";
		$colonnina_risorse.= "<tr><td valign=top>potere : </td><td valign=top><input NAME=\"new_cata[potere]\" value=\"4\" TYPE=Text SIZE='3 MAXLENGTH='3'></td></tr>";
		$colonnina_risorse.= "<tr><td valign=top>prestigio : </td><td valign=top><input NAME=\"new_cata[prestigio]\" value=\"3\" TYPE=Text SIZE='3 MAXLENGTH='3'></td></tr>";
		$colonnina_risorse.= "</table>";
	
		$colonnina_caratteristiche.= "<table border='0'>";
		$colonnina_caratteristiche.= "<tr><td valign=top colspan=2><span class=titoletto>Scegli 5 doti:</span><br></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>astuzia </td><td valign=top><input type=checkbox NAME=\"new_cata[car_astuzia]\" checked></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>capacita' lavorativa </td><td valign=top><input type=checkbox NAME=\"new_cata[car_capacita]\" checked></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>carisma </td><td valign=top><input type=checkbox NAME=\"new_cata[car_carisma]\" checked></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>competenza </td><td valign=top><input type=checkbox NAME=\"new_cata[car_competenza]\" checked></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>conoscenze </td><td valign=top><input type=checkbox NAME=\"new_cata[car_conoscenze]\" checked></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>fortuna </td><td valign=top><input type=checkbox NAME=\"new_cata[car_fortuna]\"></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>intelligenza </td><td valign=top><input type=checkbox NAME=\"new_cata[car_intelligenza]\" ></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>salute </td><td valign=top><input type=checkbox NAME=\"new_cata[car_salute]\" ></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>senso politico </td><td valign=top><input type=checkbox NAME=\"new_cata[car_senso]\" ></td></tr>";
		$colonnina_caratteristiche.= "<tr><td valign=top>spregiudicatezza </td><td valign=top><input type=checkbox NAME=\"new_cata[car_spregiudicatezza]\" ></td></tr>";
		$colonnina_caratteristiche.= "</table>";
	
		$contenuto_testo.= "<tr>
			<td valign=top width=47%><img src=\"imma/DOTtras.gif\" width=250 height=1><br>$colonnina_caratteristiche</td>
			<td valign=top width=47%><img src=\"imma/DOTtras.gif\" width=250 height=1><br>$colonnina_risorse</td>
			</tr>";

		// se è vecchio utente questi valori sono già scritti così riutilizza la vecchia partita
		$contenuto_testo.= "<tr><td valign=top colspan=3><hr><span class=testo>Per poter riprendere la partita tra qualche giorno: </span><input type='hidden' name='new_cata[IDoggetto]' value='$IDpartita'></td></tr>";
		$contenuto_testo.= "<tr><td valign=top><span class=testo>login:  </span></td><td valign=top><input NAME=\"new_cata[login]\" value=\"$cata[login]\" TYPE=Text SIZE='30' MAXLENGTH='30'></td></tr>";
		$contenuto_testo.= "<tr><td valign=top><span class=testo>password:  </span></td><td valign=top><input NAME=\"new_cata[password]\" value=\"$cata[password]\" TYPE=Text SIZE='30' MAXLENGTH='30'></td></tr>";
		$contenuto_testo.= "<tr><td valign=top><span class=testo>email: </span></td><td valign=top><input NAME=\"new_cata[email]\" value=\"$cata[email]\" TYPE=Text SIZE='30' MAXLENGTH='30'></td></tr>";
	
	$contenuto_testo.= "<tr><td valign=top colspan=3>";
	$contenuto_testo.= "	<hr>";
	$contenuto_testo.= "<input type='hidden' name='dbmusato' value='PARTITE'>";
	$contenuto_testo.= "<input type='hidden' name='s' value='$s'>";
	$contenuto_testo.= "<input type='hidden' name='gs' value='$gs'>";
	$contenuto_testo.= "<input type='hidden' name='az' value='controlla_nuova_partita'>";
	$contenuto_testo.= "<input type='submit' value=' Invia'>";
	$contenuto_testo.= "</td></tr>";
	$contenuto_testo.= "</table>";
	$contenuto_testo.= "</form>";
	$contenuto_testo.= "</div>";

	$contenuto.=$contenuto_testo;
	html($contenuto, $messaggio);

endif;

// nuova partita -----------------------------------------------------------------
if($az=="istruzioni"): 

	$contenuto_testo.= "<div align=left>";
	$contenuto_testo.= "<table border='0' width=100%>";

	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=titolo>Istruzioni</span></td></tr>";
	$contenuto_testo.= "<tr><td valign=top colspan=2 ><span class=testo>
					<hr>
					<blockquote>
					<i>Queste sono le istruzioni originali del libro; in corsivo ho aggiunto una nota per l'uso sul sito.</i>
					</blockquote>
					</span></td></tr>";
	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=testo>
					<hr>
					<b>Introduzione</b><br>
					Siete una persona come tante, n&eacute; giovane, n&eacute; vecchia, n&eacute; povera, n&eacute; ricca, con alcune doti di natura e con una tranquilla professione esercitata nella citt&agrave; in cui siete nato. <br>
					Una sera, sar&agrave; forse colpa del caldo, non riuscite a prendere sonno: prendete in mano un libro posto sul comodino accanto al letto, che vi hanno regalato tempo addietro e che non avete finora mai degnato di uno sguardo.<br>
					Il libro dipinge con poca seriet&agrave; l'atmosfera politica che si respira in Italia. D'improvviso, come per gioco, balena davanti alla vostra mente una sfida assurda: diventare Presidente del Consiglio e salvare finalmente le sorti del Paese! <br>
					Da questo momento non avete pi&ugrave; pace, il labirinto della politica italiana vi attira irreparabilmente e siete certo che non ne uscirete vivo, a meno di non portare a termine la vostra missione.<br>
					</span></td></tr>";

	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=testo>
					<hr>
					<b>Il gioco</b><br>
					La vostra attivit&agrave; politica si snoda attraverso una successione di episodi, chiamati \"note\" e numerati, che si rimandano l'uno all'altro. <i>Sul sito sono le pagine di volta in volta visualizzate e numerate.</i><br>
					A volte sarete voi a scegliere liberamente con quale nota proseguire la lettura, tra varie alternative proposte; altre volte saranno le circostanze ad obbligarvi a seguire una certa strada, ma comunque mai, in nessun momento, interverr&agrave; il caso: in questo gioco non esistono dadi. <br>
					La vostra carriera politica sar&agrave; determinata esclusivamente dalle vostre scelte, dalle doti e dalle caratteristiche che formano il vostro bagaglio personale. <br>
					Proprio per questo sar&agrave; importante la fase di creazione iniziale del vostro personaggio.<br>
					Un ultimo consiglio: evitate la lettura in sequenza delle note del libro, perch&eacute; non risulterebbe utile a cogliere indicazioni su come uscire dal labirinto e toglierebbe in parte la sorpresa e con essa il gusto dei gioco.
					</span></td></tr>";

	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=testo>
					<hr>
					<b>Le doti</b><br>
					Sta a voi definire le DOTI del vostro personaggio, che resteranno inalterate durante tutta la durata della vostra carriera. <br>Potete sceglierne liberamente cinque tra le dieci seguenti:<br>
					<ul>
					<li>astuzia <br>
					<li> capacita' lavorativa<br>
					<li> carisma<br>
					<li> competenza<br>
					<li> conoscenze<br>
					<li> fortuna<br>
					<li> intelligenza<br>
					<li> salute<br>
					<li> senso politico<br>
					<li> spregiudicatezza <br>
					</ul>
					Ci sono 252 modi diversi di scegliere le cinque caratteristiche, ma non tutte queste scelte si riveleranno altrettanto utili nello snodarsi della vostra vicenda politica.<br>
					 Effettuata la scelta, non dimenticate di segnare una crocetta accanto alle doti prescelte sul diario di gara riportato pi&ugrave; avanti. <br>
					&Egrave; consigliabile utilizzare una fotocopia o scrivere a matita, perch&eacute; l'esperienza vi potr&agrave; suggerire di ricominciare con una scelta diversa...<br><i>Qui viene effettuata la scelta all'inizio e registrata dal sito, senza bisogno di scriverla su un foglio.</i>
					</span></td></tr>";

	$contenuto_testo.= "<tr><td valign=top colspan=2><span class=testo>
					<hr>
					<b>Le caratteristiche</b><br>
					Oltre alle doti, il vostro personaggio dovr&agrave; fare attentamente i conti con quattro CARATTERISTICHE indubbiamente necessarie per svolgere con successo l'attivit&agrave; politica:<br>
					<ul>
					<li> denaro
					<li> popolarita'  
					<li> potere
					<li> prestigio 
					</ul>
					A differenza delle doti, che restano fisse durante tutta l'avventura, le caratteristiche evolvono a seconda degli eventi, e sono rappresentate ciascuna da un punteggio, che pu&ograve; variare in positivo o in negativo. <br>
					Per giungere a determinati traguardi bisogner&agrave; essere ben forniti nelle varie caratteristiche: al contrario, se anche una sola di queste scendesse a valori negativi, la vostra scalata politica si interromperebbe definitivamente. <br>
					Per questa ragione nella fase iniziale di creazione del personaggio avete a disposizione 15 punti, da ripartire a vostro piacimento tra le quattro caratteristiche citate. <br>
					Anche qui non esiste alcun vincolo (i punti possono essere anche attribuiti tutti ad una stessa caratteristica), ma non tutte le ripartizioni iniziali vi consentiranno di arrivare fino in fondo. <br>
					Segnate comunque la ripartizione iniziale scelta dei punteggi sul diario di gara.<i>Nel sito i valori delle caratteristiche sono aggiornati in modo automatico: ma non dare per scontato che sia sempre giusto, e perfavore segnala gli errori</i><br>
					L'aggiornamento dei punteggi delle caratteristiche nel diario di gara dovr&agrave; essere effettuato nel momento in cui verr&agrave; indicato nel corpo di una nota. I nuovi punteggi diventeranno immediatamente effettivi e quindi le note successive faranno riferimento ai valori cos&igrave; modificati.<br>
					Un'avvertenza importante riguarda le note in cui si richiede la presenza di una condizione oppure una seconda condizione: in questo caso, salvo che sia espressamente indicato, il test &egrave; positivo anche se si verificano entrambe le condizioni.<br>
					La creazione del personaggio e le informazioni che vi potranno essere utili sono tutte qui: sappiate che il numero di \"storie\" diverse che si snodano in questo libro si misura in termini di milioni, ma soltanto alcuni percorsi portano alla poltrona di Presidente del Consiglio. <br>
					Incominciate a leggere dalla nota numero 1 e buona fortuna!

					</span></td></tr>";

	

	$contenuto.=$contenuto_testo;
	html($contenuto, $messaggio);

endif;

// controlla nuova partita -----------------------------------------------------------------
if($az=="controlla_nuova_partita"): 
	// dovrebbe verificare che siano stati scelti parametri giusti
	// verificare che login sia unica (riconosciamo anche la partita da qui)
	// se tutto va bene
	$az="inserisco_nuova_partita";
endif;

// inserisco nuova partita ---------------------------------------------------------
if($az=="inserisco_nuova_partita"): 

		//DA FARE: verifica che login sia unica
		
		// inserisco i valori mancanti
		$new_cata[IDutente]=$sess[IDutente];
		$new_cata[data_inizio]=time();
		$new_cata[DATA_creazione]=time();
		$new_cata[DATA_modifica]=time();
		$new_cata[ultimo_utente]="$gs";
		
		$dbm = dbmmopen("dati/PARTITE","w");
			if($new_cata[IDoggetto]==""): // se è nuova dò nuovo numero
				$numeroquesto=1+dbmmfetch($dbm,"contatore");
				dbmmreplace($dbm,"contatore",$numeroquesto);
				$new_cata[IDoggetto]=$numeroquesto; 
			endif;
			dbmmreplace($dbm, $new_cata[IDoggetto], serialize($new_cata));  
		dbmmclose($dbm);
		
		// preparo elenco caratteristiche per email
		$array_caratteristiche=array("car_astuzia", "car_capacita", "car_carisma", "car_competenza", "car_conoscenze", "car_fortuna", "car_intelligenza", "car_salute", "car_senso", "car_spregiudicatezza");
		while ( list( $key, $val ) = each( $array_caratteristiche ) ):
			if($new_cata[$val]=="on"):
				$val=ermeg_replace("car_", "", $val);
				if($val=="senso"): $val="senso politico"; endif;				
				if($val=="capacita"): $val="capacita' lavorativa"; endif;
				$elenco_caratteristiche.= "$val\n\t";
			endif;
		endwhile;

		// mando email per memoria
		$testo_mail="Nuova partita a \"Il Presidente del Consiglio sei TU\"\n\t
			\n\t
			il gioco si trova su http://www.edilnol.com/o/presidente/gioco_presidente.php?login=$new_cata[login]&psw=$new_cata[password]&az=trova_vecchia_partita\n\t
			\n\t
			iniziata il: ".date("d-m-y")."\n\t
			login: $new_cata[login]\n\t
			password: $new_cata[password]\n\t
			\n\t
			RISORSE:\n\t
			denaro: $new_cata[denaro]\n\t
			popolarita: $new_cata[popolarita]\n\t
			potere: $new_cata[potere]\n\t
			prestigio: $new_cata[prestigio]\n\t
			\n\t
			CARATTERISTICHE:\n\t
			$elenco_caratteristiche
			\n\t
			Buon divertimento!
			\n\t
			\n\t
			";
		
		$testo_mail=ermeg_replace("	", "", $testo_mail);
		
		mail("marcello@balbo.net", "nuova partita su PRESIDENTE", $testo_mail, "From: presidente");		
		mail("$new_cata[email]", "Nuova partita a \"Il Presidente del Consiglio sei TU\"", $testo_mail, "From: marcello@balbo.net");		

		// gli dò l'IDpartita appena immessa e IDoggetto=391 (prima nota)
		// così va direttametne all'inizio
		$IDpartita=$new_cata[IDoggetto];
		$IDoggetto=391;
endif;

// DEFAULT: MOSTRO NOTA $IDoggetto ---------------------------------------------
	// PRENDO I DATI
	$dbm = dbmmopen("dati/PRESIDENTE","r");
		$presidente=unserialize(dbmmfetch($dbm,"$IDoggetto"));
	dbmmclose($dbm);	
	
		// effettuo le variazioni di parametri legate questa nota
		// non funziona ancora bene se torna indietro e poi ricarica la pagina
		$dbm = dbmmopen("dati/PARTITE","r");
			$attuale=unserialize(dbmmfetch($dbm,$IDpartita));
		dbmmclose($dbm);
		
		$mosse=explode("*", strrev($attuale[sequenza_mosse]));
		$ultima_mossa=strrev($mosse[0]);

		if($direzione=="indietro"):
			// sto tornando indietro di un passo
			// lo avverto e non arretro i valori
			$allerta.="<span class=testo><font color=red>Sei andato indietro di un passo</font></span><br><br>";
			$link_avanti="<span class=testo><a href=\"$PHP_SELF?s=$s&gs=$gs&IDpartita=$IDpartita&IDoggetto=$ultima_mossa\">avanti</a></span><br>";
			$attuale[numero_mosse]--;
			$attuale[denaro]=$attuale[denaro]-$presidente[variazione_denaro];
			$attuale[popolarita]=$attuale[popolarita]-$presidente[variazione_popolarita];
			$attuale[potere]=$attuale[potere]-$presidente[variazione_potere];
			$attuale[prestigio]=$attuale[prestigio]-$presidente[variazione_prestigio];
			unset($mosse[0]);		
			$attuale[sequenza_mosse]=strrev(implode("*",$mosse));	
		else:
			// sta procedendo normalmente
			//verifico che non sia stata ricaricata l'ultima pagina
			if($ultima_mossa==$IDoggetto):
				// sta ricaricando la stessa pagina
				// lo avverto e non modifico i valori
				$allerta.= "<span class=testo><font color=red>Hai ricaricato la stessa pagina</font></span><br><br>";
			else:	
				$attuale[numero_mosse]++;
				$attuale[denaro]=$attuale[denaro]+$presidente[variazione_denaro]; if($attuale[denaro]<=0): $colore_denaro="red"; endif;
				$attuale[popolarita]=$attuale[popolarita]+$presidente[variazione_popolarita]; if($attuale[popolarita]<=0): $colore_popolarita="red"; endif;
				$attuale[potere]=$attuale[potere]+$presidente[variazione_potere]; if($attuale[potere]<=0): $colore_potere="red"; endif;
				$attuale[prestigio]=$attuale[prestigio]+$presidente[variazione_prestigio]; if($attuale[prestigio]<=0): $colore_prestigio="red"; endif;
	 			$attuale[sequenza_mosse].="*$IDoggetto";
			endif;	
		endif;

		// costruisco la colonna situazione  --------------------------------------------------------------------
		// in cui è riassunta la situazione del giocatore
		
		$colonna_situazione.= "<div align=right style=\"background:silver\">";
		$colonna_situazione.= "<span class='testopiccolo'>passi:&nbsp;$attuale[numero_mosse]</span><br>";
		$colonna_situazione.="<a href='$PHP_SELF?IDoggetto=$ultima_mossa&direzione=indietro&gs=$gs&ln=$ln&s=$s&IDpartita=$IDpartita&'>indietro</a><br>";
		$colonna_situazione.=$link_avanti;
		$colonna_situazione.= "<hr>";
		
		$colonna_situazione.= "<span class='testopiccolo'><font color=$colore_denaro>denaro:&nbsp;$attuale[denaro]</font></span><br>";
		$colonna_situazione.= "<span class='testopiccolo'><font color=$colore_popolarita>popolarita':&nbsp;$attuale[popolarita]</font></span><br>";
		$colonna_situazione.= "<span class='testopiccolo'><font color=$colore_potere>potere:&nbsp;$attuale[potere]</font></span><br>";
		$colonna_situazione.= "<span class='testopiccolo'><font color=$colore_prestigio>prestigio:&nbsp;$attuale[prestigio]</font></span><br>";
		$colonna_situazione.= "<hr>";
		
		// le caratteristiche fisse
		$array_caratteristiche=array("car_astuzia", "car_capacita", "car_carisma", "car_competenza", "car_conoscenze", "car_fortuna", "car_intelligenza", "car_salute", "car_senso", "car_spregiudicatezza");
		
		while ( list( $key, $val ) = each( $array_caratteristiche ) ):
			if($attuale[$val]=="on"):
				$val=ermeg_replace("car_", "", $val);
				if($val=="senso"): $val="senso politico"; endif;				
				if($val=="capacita"): $val="capacita' lavorativa"; endif;
				$colonna_situazione.= "<span class='testopiccolo'>$val</span><br>";
			endif;
		endwhile;
		$colonna_situazione.= "<hr>";

		$colonna_situazione.="<a href='$PHP_SELF?gs=$gs&ln=$ln&s=$s&az=nuova_partita&IDpartita=$IDpartita'>nuova partita</a><br>";
		$colonna_situazione.="<a href='$PHP_SELF?IDoggetto=$IDoggetto&gs=$gs&ln=$ln&s=$s&IDpartita=$IDpartita&az=segnala'>segnala bug</a><br>";
		$colonna_situazione.= "</div>";

		// link per debug -----------------------------------------------------------------------------
		$table_vai.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
		$table_vai.= "<table border='0'>";
		$table_vai.= "<tr><td valign=top><input NAME=\"IDoggetto\" value=\"vai a\" TYPE=Text SIZE='4' MAXLENGTH='4'></span></td>";
	
		$table_vai.= "<td valign=top>";
		$table_vai.= "<input type='hidden' name='IDpartita' value='1'>"; // la partita di debug
		$table_vai.= "<input type='hidden' name='dbmusato' value='PARTITE'>";
		$table_vai.= "<input type='hidden' name='s' value='$s'>";
		$table_vai.= "<input type='hidden' name='gs' value='$gs'>";
		$table_vai.= "<input type='submit' value=' vai'>";
		$table_vai.= "</td></tr>";
		$table_vai.= "</table>";
		$table_vai.= "</td></tr></table>";
		$table_vai.= "</form>";
		$table_vai.= "<br>";
		
		if($gs=="admin"): 
			$colonna_situazione.= "<hr>";
			$colonna_situazione.=$table_vai;
			$attuale[sequenza_mosse]=""; // di questa partita non salvo le mosse 
			$attuale[numero_mosse]=""; // di questa partita non salvo le mosse 
		endif;
		
		// salvo la situazione attuale  -------------------------------------------------------------------
		$dbm = dbmmopen("dati/PARTITE","w");
			dbmmreplace($dbm, $IDpartita, serialize($attuale));  
		dbmmclose($dbm);
	
		// costruisco testo con link  --------------------------------------------------------------------
		$testo_array=explode("ai a ", $presidente[testo01]);
		$testo="";
		reset($testo_array);
		next($testo_array);
		$testo_finale=$testo_array[0];
		while ( list( $key, $val ) = each( $testo_array ) ):
			$tuty=strtok($val, " ,.;:\)");
			$testo2="ai a <a href='$PHP_SELF?IDoggetto=$tuty&gs=$gs&ln=$ln&s=$s&IDpartita=$IDpartita'>$tuty</a>";
			$new_val=ermeg_replace($tuty, $testo2, $val);
			$testo_finale.=$new_val;
		endwhile;
		
		$vedo_nota.= "$allerta";
		$vedo_nota.= "<span class='titolo'>$presidente[IDoggetto]</span><br><br>";
		if(file_exists($presidente[foto01])): $vedo_nota.= "<img SRC='$presidente[foto01]' align=right HSPACE='10' VSPACE='3' BORDER='0'>"; endif;
		
		$testo_finale=str_replace(". ", ".<br><br>", $testo_finale);
//		$testo_finale=str_replace("?", "?<br>", $testo_finale); NO per link
		$testo_finale=str_replace("!", "!<br>", $testo_finale);
		$testo_finale=str_replace("<br>Se", "<br><br>Se", $testo_finale);

		$vedo_nota.= "<span class='testo'>$testo_finale</span><br>";

		// preparo il testo per debug --------------------------------------------------------------------
		$presidente[testo01]=stripslashes($presidente[testo01]);
		$table_debug.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
		$table_debug.= "<table border='0'>";
		$table_debug.= "<tr><td valign=top><textarea NAME=\"nuovo_testo01\" rows=\"15\" cols=\"65\">$presidente[testo01]</textarea></span> </td></tr>";
		$table_debug.= "<tr><td valign=top><input NAME=\"nuova_variazione_denaro\" value=\"$presidente[variazione_denaro]\" TYPE=Text SIZE='4' MAXLENGTH='4'> <span class=testo>denaro</span></td></tr>";
		$table_debug.= "<tr><td valign=top><input NAME=\"nuova_variazione_popolarita\" value=\"$presidente[variazione_popolarita]\" TYPE=Text SIZE='4' MAXLENGTH='4'> <span class=testo>popolarita'</span></td></tr>";
		$table_debug.= "<tr><td valign=top><input NAME=\"nuova_variazione_potere\" value=\"$presidente[variazione_potere]\" TYPE=Text SIZE='4' MAXLENGTH='4'> <span class=testo>potere</span></td></tr>";
		$table_debug.= "<tr><td valign=top><input NAME=\"nuova_variazione_prestigio\" value=\"$presidente[variazione_prestigio]\" TYPE=Text SIZE='4' MAXLENGTH='4'> <span class=testo>prestigio</span></td></tr>";

		$table_debug.= "<td valign=top>";
		$table_debug.= "<input type='hidden' name='IDoggetto' value='$IDoggetto'>"; // 
		$table_debug.= "<input type='hidden' name='IDpartita' value='1'>"; // la partita di debug
		$table_debug.= "<input type='hidden' name='az' value='salva_nota'>";
		$table_debug.= "<input type='hidden' name='dbmusato' value='PARTITE'>";
		$table_debug.= "<input type='hidden' name='s' value='$s'>";
		$table_debug.= "<input type='hidden' name='gs' value='$gs'>";
		$table_debug.= "<input type='submit' value=' salva'>";
		$table_debug.= "</td></tr>";
		$table_debug.= "</table>";
		$table_debug.= "</td></tr></table>";
		$table_debug.= "</form>";
		$table_debug.= "<br>";
		
	// monto la pagina
	$contenuto.= "<table width='100%' border=0 cellpadding=10 cellspacing=0 height=400>";		
	$contenuto.= "<tr><td valign=top>";
		$contenuto.= "$vedo_nota";
		if($gs=="admin"): $contenuto.="<hr>$table_debug"; endif;
	$contenuto.= "</td></tr>";	
	$contenuto.= "</table>";		

	$colonna_destra.= "<table bgcolor=silver width='100%' border=0 cellpadding=5 cellspacing=0 height=400>";		
	$colonna_destra.= "<tr><td valign=top>";
	$colonna_destra.= "<img SRC='imma/DOTtras.gif' HSPACE='0' VSPACE='' BORDER='0' height=1 width=140>";
		$colonna_destra.= $colonna_situazione;	
	$colonna_destra.= "</td></tr>";	
	$colonna_destra.= "</table>";		
	
	$contenuto=stripslashes($contenuto);
	html($contenuto, $messaggio);





