<?php  
// css
$dimensione_titoletto=$stil[DIMENSIONE_TITOLO]-1; $dimensione_titoletto.="px";
$dimensione_testopiccolo=$stil[DIMENSIONE_TESTO]-1; $dimensione_testopiccolo.="px";
$dimensione_testo="$stil[DIMENSIONE_TESTO]"."px";
$dimensione_titolo="$stil[DIMENSIONE_TITOLO]"."px";
$dimensione_liste="$stil[DIMENSIONE_LISTE]"."px";

echo "
<style type='text/css'>
<!--
a.testopiccolo {color: #000000; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testopiccolo}
a.testo {color: #$stil[COLORE_TESTO]; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
a.titoletto {color: #$stil[COLORE_TITOLO]; text-decoration:bold; font-family: $stil[FONT_TITOLO]; font-weight: bold; font-size: $dimensione_titoletto}
a.titolo {color: #$stil[COLORE_TITOLO]; text-decoration:bold; font-family: $stil[FONT_TITOLO]; font-weight: bold; font-size: $dimensione_titolo}
a.liste {color: #$stil[COLORE_LISTE]; text-decoration:none; font-family: $stil[FONT_LISTE]; font-weight: bold; font-size: $dimensione_liste}

.testo {line-height:1.6em;}

a.navigazione {color: #ffffff; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}

span.testopiccolo {color: #000000; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testopiccolo}
span.testo {color: #$stil[COLORE_TESTO]; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
A {color: #$stil[COLORE_TESTO]; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
A:hover {color: #996699; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
A:active {color: #009900; text-decoration:none; font-family: $stil[FONT_TESTO]; font-size: $dimensione_testo}
span.titoletto {color: #$stil[COLORE_TITOLO]; text-decoration:bold; font-family: $stil[FONT_TITOLO]; font-weight: bold; font-size: $dimensione_titoletto}
span.titolo {color: #$stil[COLORE_TITOLO]; text-decoration:bold; font-family: $stil[FONT_TITOLO]; font-weight: bold; font-size: $dimensione_titolo}
span.liste {color: #$stil[COLORE_LISTE]; text-decoration:none; font-family: $stil[FONT_LISTE]; font-weight: bold; font-size: $dimensione_liste}
-->
</style>
";

?>