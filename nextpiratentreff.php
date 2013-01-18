<?php

require 'class.iCalReader.php';

$gevents0 = array();

function piratentools_npt_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$options = get_option("piratentools-npt");
	
	if ($_POST['pt-npt-action'] == "add") {
		$nextid = $options["nextid"]++;
		$upd_calurl = $_POST['pt-npt-add-calurl'];
		$upd_searchstring = $_POST['pt-npt-add-searchstring'];
		$options["content"][$nextid]["calurl"] = $upd_calurl;
		$options["content"][$nextid]["searchstring"] = $upd_searchstring;
		$options["content"][$nextid]["offset"] = $upd_offset;
		update_option("piratentools-npt", $options);
	}
	
	if ($_POST['pt-npt-action'] == "del") {
		$nextid = $options["nextid"]++;
		$del_ids = $_POST['pt-npt-del'];
		
		foreach ($del_ids as $key => $val) {
			if ($val == 1) {
				unset($options["content"][$key]);
			}
		}
		update_option("piratentools-npt", $options);
	}
	
	echo '<div class="wrap">';

	?>
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
	<?
	echo '</div>';
}

function icsToArray($paramUrl) {
    $icsFile = file_get_contents($paramUrl);

    $icsData = explode("BEGIN:", $icsFile);

    foreach($icsData as $key => $value) {
        $icsDatesMeta[$key] = explode("\n", $value);
    }

    foreach($icsDatesMeta as $key => $value) {
        foreach($value as $subKey => $subValue) {
            if ($subValue != "") {
                if ($key != 0 && $subKey == 0) {
                    $icsDates[$key]["BEGIN"] = $subValue;
                } else {
                    $subValueArr = explode(":", $subValue, 2);
                    $icsDates[$key][$subValueArr[0]] = $subValueArr[1];
                }
            }
        }
    }

    return $icsDates;
}

function piratentools_npt_shortcode($atts) {
	global $gevents0;
	
	$id = $atts['id'];
	$options = get_option("piratentools-npt");
	
	$searchstring = $options['content'][$id]['searchstring'];
	$calurl = $options['content'][$id]['calurl'];
	$offset = $options['content'][$id]['offset'];
	$remove = $atts['remove'];
	
	$ical = new ical($calurl);
	
	if ($gevents0[$id]) {
		$events0 = $gevents0[$id];
	} else {
		$events = $ical->events();
		
		foreach ($events as $key => $val) {
			if (strpos(" ".$val['SUMMARY'], $searchstring) != 0) {
				if (!$events0 || (($ical->iCalDateToUnixTimestamp($val['DTSTART']) < $ical->iCalDateToUnixTimestamp($events0['DTSTART'])) && ($ical->iCalDateToUnixTimestamp($val['DTSTART']) > (time()-$offset)))) {
					$events0 = $val;
				}
			}
		}
		$gevents0[$id] = $events0;
	}

	$e_start = $ical->iCalDateToUnixTimestamp($events0['DTSTART'])+(get_option('gmt_offset')*3600);
	$e_end = $ical->iCalDateToUnixTimestamp($events0['DTEND'])+(get_option('gmt_offset')*3600);
	$e_location = $events0['LOCATION'];
	$e_description = $events0['DESCRIPTION'];
	$e_summary = $events0['SUMMARY'];
	$e_summary2 = trim(str_replace($remove, "", $e_summary));
	if ($events0) {
		$string = $atts['output'];
		$string = str_replace("%TITEL2%", $e_summary2, $string);
		$string = str_replace("%TITEL%", $e_summary, $string);
		$string = str_replace("%ORT%", $e_location, $string);
		//$string = str_replace("%DATUM", date("d.m.Y H:i", $e_start+(get_option('gmt_offset')*3600)), $string);
		$string = preg_replace('/(\{(.*?)})/e', 'date("$2", $e_start)', $string);
		return $string;
	} else {
		return $atts['else'];
	}
}

add_shortcode( "pt-npt", "piratentools_npt_shortcode");
?>