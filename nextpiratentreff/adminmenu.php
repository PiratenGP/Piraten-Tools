<div class="wrap">

		<h2>Piraten-Tools › Next Piratentreff</h2>
		<form method="POST">
		<h3>Neuer Eintrag</h3>
		<table  class="pttable">
			<tr>
				<td>Kalender-URL:</td>
				<td><input type="text" size="100" name="pt-npt-add-calurl" /><br />
				<small>URL zu einer iCal-Datei</small></td>
			</tr>
			<tr>
				<td>Suchbegriff:</td>
				<td><input type="text" name="pt-npt-add-searchstring" /><br />
				<small>Danach wird in den Termin-Titeln gesucht</small></td>
			</tr>
			<tr>
				<td>Offset:</td>
				<td><input type="text" name="pt-npt-add-offset" size="5" value="0" /> Minuten<br />
				<small>Dauer, wie lange der aktuelle Termin auch nach Ablauf der Startzeit angezeigt werden soll</small></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Eintragen" /></td>
			</tr>
		</table>
		<input type="hidden" name="pt-npt-action" value="add" />

		</form>
		
		<h3>Einträge</h3>
		<form method="POST">
		<table class="pttable">
			<tr>
				<td>ID</td>
				<td>Kalender-URL</td>
				<td>Suchbegriff</td>
				<td>Offset</td>
				<td>Shortcode</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				$counter = 0;
				foreach ($options["content"] as $key => $val) {
					$counter++;
					?>
					<tr>
						<td><?=$key;?></td>
						<td><?=$val["calurl"];?></td>
						<td><?=$val["searchstring"];?></td>
						<td><?=$val["offset"];?> min</td>
						<td><pre>[pt-npt id=<?=$key;?> output="{d. F Y}" else=""]</pre></td>
						<td><input type="checkbox" name="pt-npt-del[<?=$key;?>]" value="1" /></td>
					</tr>
					<?
				}
			?>
			<tr>
				<?php
				if ($counter > 0) {
				?>
				<td colspan="5">&nbsp;</td>
				<td><input type="submit" value="L&ouml;schen" /></td>
				<?php
				} else {
				?>
				<td colspan="6">Keine Einträge</td>
				<?php
				}
				?>
			</tr>
		</table>
		<input type="hidden" name="pt-npt-action" value="del" />
		</form>
		
		<h3>Anleitung</h3>
		<p><ol><li>Einen neuen Eintrag erstellen mit der URL zu der ics-Datei, dem gewünschten Suchbegriff (z.B. "Piratentreff), und dem gemünschten Offset*</li><li>Shortcode [pt-npt] in die gewünschten Seiten einbauen.</li></ol>Folgende Parameter können verwendet werden: (X ist Platzhalter)
		<table class="pttable">
		<tr><td>id=X</td><td>Die ID des Eintrags, s.o.</td></tr>
		<tr><td>output="X"</td><td>Was ausgegeben werden soll.</td></tr>
		<tr><td>remove="X"</td><td>Wird aus dem Titel herausgelöscht, s.u.</td></tr>
		<tr><td>else="X"</td><td>Wird ausgegeben, wenn kein Termin gefunden wurde</td></tr>
		<tr><td>skip=X</td><td>So viele Termine werden bei der Suche übersprungen</td></tr>
		</table>
		<br />Beim Parameter "output" können folgende Variablen verwendet werden:
		<table class="pttable">
		<tr><td>%TITEL%</td><td>Der Titel des Eintrags</td></tr>
		<tr><td>%TITEL2%</td><td>Der Titel des Eintrags, der Teil der mit "remove" definiert wurde wird entfernt</td></tr>
		<tr><td>%ORT%</td><td>Der Ort des Eintrags</td></tr>
		<tr><td>{X}</td><td>In geschweiften Klammern können PHP-Datumsangaben definiert werden, für <em><?php echo date("d.m.Y"); ?></em> also zum Beispiel <em>{d.m.Y}</em></td></tr>
		</table>
		</p>
	</div>