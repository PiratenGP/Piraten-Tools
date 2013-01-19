<div class="wrap">

		<h2>Piraten-Tools › Next Piratentreff</h2>
		<form method="POST">
		<h3>Neue Suchmaske</h3>
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
		
		<h3>Suchmasken</h3>
		<form method="POST">
		<table class="pttable">
			<tr>
				<td>ID</td>
				<td>Kalender-URL</td>
				<td>Suchbegriff</td>
				<td>Offset</td>
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
						<td><input type="checkbox" name="pt-npt-del[<?=$key;?>]" value="1" /></td>
					</tr>
					<?
				}
			?>
			<tr>
				<?php
				if ($counter > 0) {
				?>
				<td colspan="4">&nbsp;</td>
				<td><input type="submit" value="L&ouml;schen" /></td>
				<?php
				} else {
				?>
				<td colspan="5">Keine Einträge</td>
				<?php
				}
				?>
			</tr>
		</table>
		<input type="hidden" name="pt-npt-action" value="del" />
		</form>
	</div>