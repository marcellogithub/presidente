<?php  

// GESTIONE: MODIFICA DATI SINGOLO IDOGGETTO -----------------------------------------------------	
// identica al pezzo in _cat_azioni.incl, serve per fare presto i singoli
// edita form per modificare un oggetto
// arriva:
//			az - modifica
//			gs - non vuoto
//			dbmusato - il nome del dbm utilizzato
//			IDoggetto - l'oggetot da editare
// esce:
// 		az - controlla - quando invia i dati per immissione


		// mostro il singolo in condizioni di emergenza, senza il file specifico per questa tabella
		$contenuto.= "<span class='titoletto'><b>Modifica dati singolo oggetto</b></span>";
		$contenuto.= "<hr>";
		$contenuto_intestazione.= "<a href=\"javascript:history.go(-1);\">torna indietro</a>";
		$contenuto.= "<a href='$PHP_SELF?gs=$gs&s=$s&az=nuovo&dbmusato=$dbmusato'>nuovo oggetto</a>";
		$contenuto.= "<hr>";
			
		// PRENDO I DATI
		$dbm = dbmmopen("dati/$dbmusato","r");
			$cata=unserialize(dbmmfetch($dbm,"$IDoggetto"));
			$c_cata=unserialize(dbmmfetch($dbm,"campi"));
			$t_cata=unserialize(dbmmfetch($dbm,"tipi"));
			$n_cata=unserialize(dbmmfetch($dbm,"nomi"));
			$v_cata=unserialize(dbmmfetch($dbm,"valori"));
		dbmmclose($dbm);	
	
		$contenuto.= "<div align=right>";
		$contenuto.= "<form ENCTYPE='multipart/form-data' action='$PHP_SELF' method='POST'>";
		$contenuto.= "<table border='0'>";
			
			for ($j = 0; $j <= $c_cata[numero_campi]; $j++):
	 			$nome_campo=$c_cata[$j];
	
				// visualizzo IDoggetto
				// visualizzo DATA_modifica
				// visualizzo e salvo DATA_creazione
				$data_creazione=date(" d-m-Y H:i",$cata[DATA_creazione]);
				$data_modifica=date(" d-m-Y H:i",$cata[DATA_modifica]);
				$data_attuale=time();
				if($nome_campo=="IDoggetto"):  
					$contenuto.= "
						<tr><td valign=top align=right colspan=2>
						<span class=testo>IDoggetto: $cata[$nome_campo] <input NAME=\"new_cata[$nome_campo]\" value=\"$cata[$nome_campo]\" TYPE='hidden'><br>
						<font size=1 color=999999>creato il: $data_creazione</font><br>
						<font size=1 color=999999>ultima modifica: $data_modifica</font> <input NAME=\"new_cata[DATA_modifica]\" value=\"$data_attuale\" TYPE='hidden'><br>
						<font size=1 color=999999>Ultimo utente: $cata[$nome_campo]</font><input NAME=\"new_cata[ultimo_utente]\" value='$gs' TYPE='hidden'>
						</span>
						</td></tr>";
				endif;
	
				// visualizzo campo di testo
				if($t_cata[$nome_campo]=="t" && $nome_campo!="IDoggetto"):  
	
					$cata[$nome_campo]=stripslashes($cata[$nome_campo]); 
					$cata[$nome_campo]=ermeg_replace("\"", "''", $cata[$nome_campo]); 
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><input NAME=\"new_cata[$nome_campo]\" value=\"$cata[$nome_campo]\" TYPE=Text SIZE='40 MAXLENGTH='80'></td></tr>"; 
				endif;
	
				// visualizzo campo relazionato o relativo
				if($t_cata[$nome_campo]=="re" || $t_cata[$nome_campo]=="rl"):
					unset($parametri_relazione);
					$parametri_relazione=explode("::",$v_cata[$nome_campo]);
						$dbm_relazionato=$parametri_relazione[0]; // il dbm nel quale sono i record da pescare
						$campo_relazione=$parametri_relazione[1]; // il campo che dice quali record sono pescabili
						$operatore_relazione=$parametri_relazione[2]; // il tipo di confronto che si fa uguale, maggiore, diverso etc
						$parametro_relazione=$parametri_relazione[3]; // il parametro da confrontare
							if($t_cata[$nome_campo]=="rl"): $parametro_relazione=$cata[$parametro_relazione]; endif;// il parametro da confrontare
						$mostro_relazione=$parametri_relazione[4]; // il campo del dbm relazionato da mostrare nel select 
					$opzioni="<option value=\" \" $sel> </option>"; // il primo è vuoto
					//trovo tutti gli IDoggetto di $dbm_relazionato che sono relazionabili
					$array_da_lavorare=file("dati/$dbm_relazionato");
					while(list($key,$val)=each($array_da_lavorare)):
						unset($due_pezzi);
						$due_pezzi=explode("?^?",$val);
						unset($cataQUI);
						$cataQUI=unserialize($due_pezzi[1]);
						
						if($operatore_relazione=="uguale"):
							if($cataQUI[$campo_relazione]=="$parametro_relazione"): 
								// e li metto come lista_option
								if ($cataQUI[IDoggetto]==$cata[$nome_campo]):
									$sel="selected";
								else:
									$sel="";
								endif;
								$opzioni=$opzioni."<option value=\"$cataQUI[IDoggetto]\" $sel>$cataQUI[$mostro_relazione]</option>";
							endif;
						endif;
						if($operatore_relazione=="contenuto"):
							if(ermegi($cataQUI[$campo_relazione], $parametro_relazione)): 
								// e li metto come lista_option
								if ($cataQUI[IDoggetto]==$cata[$nome_campo]):
									$sel="selected";
								else:
									$sel="";
								endif;
								$opzioni=$opzioni."<option value=\"$cataQUI[IDoggetto]\" $sel>$cataQUI[$mostro_relazione]</option>";
							endif;
						endif;
						if($operatore_relazione=="maggiore"):
							if($cataQUI[$campo_relazione]>$parametro_relazione): 
								// e li metto come lista_option
								if ($cataQUI[IDoggetto]==$cata[$nome_campo]):
									$sel="selected";
								else:
									$sel="";
								endif;
								$opzioni=$opzioni."<option value=\"$cataQUI[IDoggetto]\" $sel>$cataQUI[$mostro_relazione]</option>";
							endif;
						endif;
						if($operatore_relazione=="minore"):
							if($cataQUI[$campo_relazione]<$parametro_relazione): 
								// e li metto come lista_option
								if ($cataQUI[IDoggetto]==$cata[$nome_campo]):
									$sel="selected";
								else:
									$sel="";
								endif;
								$opzioni=$opzioni."<option value=\"$cataQUI[IDoggetto]\" $sel>$cataQUI[$mostro_relazione]</option>";
							endif;
						endif;
						
					endwhile;	
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><select NAME=\"new_cata[$nome_campo]\"> $opzioni</select> 
							</td></tr>"; 
				endif;
	
				// visualizzo campo select
				if($t_cata[$nome_campo]=="s"):
					$opzioni="";
					$lista_option=explode("::",$v_cata[$nome_campo]);
					//$lista_option[]=$cata[$nome_campo];
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							if (trim($lista_option[$k])==$cata[$nome_campo]):
								$sel="selected";
							else:
								$sel="";
							endif;
							$opzioni=$opzioni."<option value=\"$lista_option[$k]\" $sel>$lista_option[$k]</option>";
						endif;
					endfor;				
					$opzioni="<option value=\"$cata[$nome_campo]\" selected>$cata[$nome_campo]</option>".$opzioni;
					$opzioni="<option value=\"\" $sel></option>".$opzioni;
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><select NAME=\"new_cata[$nome_campo]\"> $opzioni</select><input NAME=\"new_cata_custom[$nome_campo]\" value=\"\" TYPE=Text SIZE='20' MAXLENGTH='80'> 
							</td></tr>"; 
				endif;
	
				// visualizzo campo select_custom
				if($t_cata[$nome_campo]=="sc"):
					$opzioni="";
					$lista_option=explode("::",$v_cata[$nome_campo]);
					//$lista_option[]=$cata[$nome_campo];
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							if (trim($lista_option[$k])==$cata[$nome_campo]):
								$sel="selected";
							else:
								$sel="";
							endif;
							$opzioni=$opzioni."<option value=\"$lista_option[$k]\" $sel>$lista_option[$k]</option>";
						endif;
					endfor;				
					$opzioni="<option value=\"$cata[$nome_campo]\" selected>$cata[$nome_campo]</option>".$opzioni;
					$opzioni="<option value=\"\" $sel></option>".$opzioni;
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><select NAME=\"new_cata[$nome_campo]\"> $opzioni</select><input NAME=\"new_cata_custom[$nome_campo]\" value=\"\" TYPE=Text SIZE='20' MAXLENGTH='80'> 
								<a href='$PHP_SELF?az=modifica_select&dbmusato=$dbmusato&IDoggetto=$cata[IDoggetto]&nome_campo=$nome_campo&s=$s&gs=$gs'>modifica</a> </td></tr>"; 
				endif;
	
				// visualizzo campo checkbox
				if($t_cata[$nome_campo]=="c"):  
					if (trim($cata[$nome_campo])=="on"):
						$chk="checked";
					else:
						$chk="";
					endif;
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><input type=checkbox NAME=\"new_cata[$nome_campo]\" $chk></td></tr>"; 
				endif;
	
				// visualizzo campo radiobutton
				if($t_cata[$nome_campo]=="r"):  
					$lista_option=explode("::",$v_cata[$nome_campo]);
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top>";
					for ($k=0;$k<count($lista_option);$k++):
						if(trim($lista_option[$k])!=""):
							if (trim($lista_option[$k])== $cata[$nome_campo]):
								$chk="checked";
							else:
								$chk="";
							endif;
							$contenuto.= " $lista_option[$k] : <input type=radio NAME=\"new_cata[$nome_campo]\" value=\"$lista_option[$k]\" $chk><br>";
						endif;
					endfor;				
					$contenuto.= "</td></tr>"; 
				endif;
	
				// visualizzo campo di box testo
				if($t_cata[$nome_campo]=="b" && $nome_campo!="IDoggetto"):   $cata[$nome_campo]=stripslashes($cata[$nome_campo]); $contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><TEXTAREA NAME=\"new_cata[$nome_campo]\" ROWS=5 COLS=50 WRAP=Virtual>$cata[$nome_campo]</TEXTAREA></td></tr>"; endif;
				
				// visualizzo campo con foto
				if($t_cata[$nome_campo]=="f"):
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><input NAME=\"new_cata[$nome_campo]\" value=\"$cata[$nome_campo]\" TYPE=hidden>"; 
					if($cata[$nome_campo]!=""):
						// calcolo la foto piccola	
						settype($cata[$nome_campo], "string");
						$urlfoto="$cata[$nome_campo]";
						if(file_exists($urlfoto) && trim($urlfoto)!=""):
							$imagesize = GetImageSize("$urlfoto");
							$maxwidth=120;
							$maxheight=120;
							$percentuale=100;
							if($imagesize[0]>$maxwidth): $percentuale=round(($maxwidth / $imagesize[0]) * 100); endif;
							if($imagesize[1]>$maxheight): $percentuale=round(($maxheight / $imagesize[1]) * 100); endif;
							$pixelgiustiwidth=round(($percentuale * $imagesize[0]) / 100);
							$pixelgiustiheight=round(($percentuale * $imagesize[1]) / 100);
							srand((double)microtime()*1000000); $randval = rand();
							$contenuto.= "<img SRC='$urlfoto?' width='$pixelgiustiwidth' height='$pixelgiustiheight' HSPACE='10' VSPACE='3' BORDER='0'><br>";
						endif;
						$contenuto.= "<a href='$PHP_SELF?az=cambiafoto&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>cambia foto</a>&nbsp;|&nbsp;";
						$contenuto.= "<a href='$PHP_SELF?az=eliminafoto&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>elimina foto</a><br></td></tr>";
					else:
						$contenuto.= "<a href='$PHP_SELF?az=cambiafoto&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>inserisci foto</a></td></tr> ";				
					endif;
				endif;
	
				// visualizzo campo con file
				if($t_cata[$nome_campo]=="l"):
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><input NAME='new_cata[$nome_campo]' value='$cata[$nome_campo]' TYPE=hidden>"; 
					if($cata[$nome_campo]!=""):
						$contenuto.= "<a href='$PHP_SELF?az=cambiafile&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>cambia file</a>&nbsp;|&nbsp;";
						$contenuto.= "<a href='$PHP_SELF?az=eliminafile&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>elimina file</a><br></td></tr>";
					else:
						$contenuto.= "<a href='$PHP_SELF?az=cambiafile&s=$s&gs=$gs&campofoto=$nome_campo&IDoggetto=$cata[IDoggetto]&dbmusato=$dbmusato'>inserisci file</a></td></tr> ";				
					endif;
				endif;
	
				// visualizzo campo array | e lo mantengo col campo hidden 
				if($t_cata[$nome_campo]=="a"): 
					$contenuto.= "<tr><td valign=top><span class=testo>$nome_campo: </span></td><td valign=top><a href='$PHP_SELF?az=modifica_array&dbmusato=$dbmusato&IDoggetto=$cata[IDoggetto]&nome_campo=$nome_campo&s=$s&gs=$gs'>modifica</a><input NAME='duplica' value='$nome_campo' TYPE='hidden'><input NAME='duplica_id' value='$IDoggetto' TYPE='hidden'></td></tr>"; 
				endif;
				
				// visualizzo campo con data (data che viene immessa dall'utente)
				// è registrato in tre pezzi
				if($t_cata[$nome_campo]=="d"):
				
					$lista_date=explode("::",$cata[$nome_campo]); // giorno::mese::anno
						$cata_giorno[$nome_campo]=$lista_date[0]; // formato "3" = terzo giorno del mese
						$cata_mese[$nome_campo]=$lista_date[1]; // formato "5" = maggio
						$cata_anno[$nome_campo]=$lista_date[2]; // formato "2004"
														
					$array_mesi=array ("vuoto", "gennaio", "febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre"); 
					$select_mese="<OPTION value=\"0\">mese";
					for($n=1; $n<12; $n++):
						$selected=""; if($n==$cata_mese[$nome_campo]): $selected="SELECTED"; endif;
						$select_mese.= "<OPTION value=\"$n\" $selected>$array_mesi[$n]";
					endfor;
					
					$select_giorno="<OPTION value=\"00\">giorno";
					for($n=1; $n<32; $n++):
						$selected=""; if($n=="$cata_giorno[$nome_campo]"): $selected="SELECTED"; endif;
						$select_giorno.= "<OPTION value=\"$n\" $selected>$n";
					endfor;
					
					$contenuto.= "
						<tr>
						<td valign=top><span class=testo>$nome_campo: </span></td>
						<td valign=top>
							<SELECT NAME=\"new_cata_giorno[$nome_campo]\" SIZE=1> $select_giorno</SELECT>
							<SELECT NAME=\"new_cata_mese[$nome_campo]\" SIZE=1> $select_mese</SELECT>
							<input NAME=\"new_cata_anno[$nome_campo]\" value=\"$cata_anno[$nome_campo]\" TYPE=Text SIZE='4 MAXLENGTH='4'>
						</td>
						</tr>
						"; 
				endif;
	
			endfor;
		
		$contenuto.= "<tr><td valign=top colspan=2>";
		$contenuto.= "<input type='hidden' name='dbmusato' value='$dbmusato'>";
		$contenuto.= "<input type='hidden' name='gs' value='$gs'>";
		$contenuto.= "<input type='hidden' name='s' value='$s'>";
		$contenuto.= "<input type='hidden' name='az' value='controlla'>";
		$contenuto.= "<input type='submit' value=' Invia '>";
		$contenuto.= "</td></tr>";
		$contenuto.= "</table>";
		$contenuto.= "</form>";
		$contenuto.= "</div>";



?>