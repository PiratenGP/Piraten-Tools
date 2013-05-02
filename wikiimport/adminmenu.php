<div class="wrap">

		<h2>Piraten-Tools › Wiki Import</h2>
		<form method="POST">
		<h3>Neuer Eintrag</h3>
		<table class="pttable">
			<tr>
				<td>Seiten-URL:</td>
				<td><input type="text" size="100" name="pt-wi-add-pageurl" /><br />
				<small>URL zu einer Wiki-Seite (vollständige URL)</small></td>
			</tr>
			<tr>
				<td>DIV-ID:</td>
				<td><input type="text" name="pt-wi-add-divid" /><br />
				<small>ID eines DIV-Containers, dessen Inhalt ausgelesen werden soll.</small></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Eintragen" /></td>
			</tr>
		</table>
		<input type="hidden" name="pt-wi-action" value="add" />
		</form>
		
		<h3>Eintr&auml;ge</h3>
		<form method="POST">
		<table class="pttable">
			<tr>
				<td>ID</td>
				<td>Seiten-URL</td>
				<td>DIV-ID</td>
				<td>Shortcode</td>
				<td>Reload-Link</td>
				<td>&nbsp;</td>
			</tr>
			<?php
				$counter = 0;
				foreach ($options["content"] as $key => $val) {
					$counter++;
					?>
					<tr>
						<td><?=$key;?></td>
						<td><?=$val["pageurl"];?></td>
						<td><?=$val["divid"];?></td>
						<td><pre>[pt-wi id=<?=$key;?>]</pre></td>
						<td><a href="<? echo get_site_url(); ?>/?ptwi_reload=<?=$key;?>&ptwi_kennwort=<?=$val["kennwort"];?>">[reload]</a></td>
						<td class="deltd"><input type="checkbox" name="pt-wi-del[<?=$key;?>]" value="1" /></td>
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
		<input type="hidden" name="pt-wi-action" value="del" />
		</form>
		<h3>Anleitung</h3>
		<p><ol><li>Einen neuen Eintrag erstellen mit der URL zu der (Wiki-)Seite und der ID des DIV-Containers, der eingelesen werden soll</li><li>In eine Wordpress-Seite oder einen Beitrag den Shortcode aus der obigen Tabelle einfügen</li><li>Immer wenn nötig den Reload-Link aufrufen (funktioniert auch, wenn man nicht eingeloggt ist, deswegen den Link vertraulich behandeln!)<br /><strong>oder:</strong> den Shortcode <pre>[pt-wi id=X reload=1]</pre> verwenden, dann wird die Seite bei jedem Aufruf aktualisiert.</li></ol></p><p><strong>Hinweis:</strong> Der automatische Reload kann zu langen Ladezeiten führen! Es wird empfohlen, entweder regelmäßig den Reload-Link manuell aufzurufen, oder alternativ die <a href="http://api.piratenpartei-bw.de">BW-Api</a> zu verwenden!</p>
	</div>