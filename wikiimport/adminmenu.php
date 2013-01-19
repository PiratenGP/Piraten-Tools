<div class="wrap">

		<h2>Piraten-Tools › Wiki Import</h2>
		<form method="POST">
		<h3>Neuer Eintrag</h3>
		<table class="pttable">
			<tr>
				<td>Seiten-URL:</td>
				<td><input type="text" size="100" name="pt-wi-add-pageurl" /><br />
				<small>URL zu einer Wiki-Seite (komplette URL)</small></td>
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
	</div>