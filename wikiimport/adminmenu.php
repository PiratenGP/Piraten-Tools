<div class="wrap">

		<h2>Piraten-Tools › Wiki Import</h2>
		<form method="POST">
		<h3>Neuer Eintrag</h3>
		<table>
			<tr>
				<td>Seiten-URL:</td>
				<td><input type="text" name="pt-wi-add-pageurl" /></td>
			</tr>
			<tr>
				<td>DIV-ID:</td>
				<td><input type="text" name="pt-wi-add-divid" /></td>
			</tr>
		</table>
		<input type="hidden" name="pt-wi-action" value="add" />
		<input type="submit" />
		</form>
		
		<h3>Eintr&auml;ge</h3>
		<form method="POST">
		<table border="1">
			<tr>
				<td>ID</td>
				<td>Seiten-URL</td>
				<td>DIV-ID</td>
				<td>Kennwort</td>
				<td>Shortcode</td>
				<td>Reload-Link</td>
				<td><input type="submit" value="L&ouml;schen" /></td>
			</tr>
			<?php
				foreach ($options["content"] as $key => $val) {
					?>
					<tr>
						<td><?=$key;?></td>
						<td><?=$val["pageurl"];?></td>
						<td><?=$val["divid"];?></td>
						<td><?=$val["kennwort"];?></td>
						<td><pre>[pt-wi id=<?=$key;?>]</pre></td>
						<td><a href="<? echo get_site_url(); ?>/?ptwi_reload=<?=$key;?>&ptwi_kennwort=<?=$val["kennwort"];?>">[reload]</a></td>
						<td><input type="checkbox" name="pt-wi-del[<?=$key;?>]" value="1" /></td>
					</tr>
					<?
				}
			?>
		</table>
		<input type="hidden" name="pt-wi-action" value="del" />
		</form>
	</div>