<div class="wrap">

		<h2>Next Piratentreff</h2>
		<form method="POST">
		<h3>Neue Suchmaske</h3>
		<table>
			<tr>
				<td>Kalender-URL:</td>
				<td><input type="text" name="pt-npt-add-calurl" /></td>
			</tr>
			<tr>
				<td>Suchbegriff:</td>
				<td><input type="text" name="pt-npt-add-searchstring" /></td>
			</tr>
			<tr>
				<td>Offset:</td>
				<td><input type="text" name="pt-npt-add-offset" value="0" /></td>
			</tr>
		</table>
		<input type="hidden" name="pt-npt-action" value="add" />
		<input type="submit" />
		</form>
		
		<h3>Suchmasken</h3>
		<form method="POST">
		<table border="1">
			<tr>
				<td>ID</td>
				<td>Kalender-URL</td>
				<td>Suchbegriff</td>
				<td>Offset</td>
				<td><input type="submit" value="L&ouml;schen" /></td>
			</tr>
			<?php
				foreach ($options["content"] as $key => $val) {
					?>
					<tr>
						<td><?=$key;?></td>
						<td><?=$val["calurl"];?></td>
						<td><?=$val["searchstring"];?></td>
						<td><?=$val["offset"];?></td>
						<td><input type="checkbox" name="pt-npt-del[<?=$key;?>]" value="1" /></td>
					</tr>
					<?
				}
			?>
		</table>
		<input type="hidden" name="pt-npt-action" value="del" />
		</form>
	</div>