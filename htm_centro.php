<?php  

echo "<!-- CENTRO -->";
echo "<div align='center'>";
echo "<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH='700'>";

	echo "<tr>";
	echo "<td bgcolor='$stil[COLORE_SFONDO_C]' valign=middle colspan=3>";
	echo "<img src='imma/DOTtras.gif' height='1' WIDTH='698' align='left'>";
	echo "&nbsp;&nbsp;$navigazione_orizzontale_template";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td bgcolor='$stil[COLORE_SFONDO_D]' valign=top WIDTH='550'>";
		echo "<table BORDER=0 CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>";
		echo "<tr>";
			echo "<td valign=top>";
			echo "$contenuto";
			echo "</td>";
		echo "</tr>";
		echo "</table>";
	echo "</td>";
	echo "<td bgcolor='$stil[COLORE_SFONDO_D]' valign=top>";
			echo "$colonna_destra";
	echo "</td>";
	echo "</tr>";

echo "</table>";
echo "</div>";
