<?php

require('ical/SG_iCal.php');

class PT_nextpiratentreff {

	static private $data = array();
	static private $found = array();
	
	static private function parsecalfile($calfile) {
		$ical = new SG_iCalReader($calfile);
		$query = new SG_iCal_Query();

		$evts = $ical->getEvents();


		$data = array();
		foreach($evts as $id => $ev) {
			$jsEvt = array(
				"id" => ($id+1),
				"title" => $ev->getProperty('summary'),
				"start" => $ev->getStart(),
				"end"   => $ev->getEnd()-1,
				"allDay" => $ev->isWholeDay()
			);

			if (isset($ev->recurrence)) {
				$count = 0;
				$start = $ev->getStart();
				$freq = $ev->getFrequency();
				if ($freq->firstOccurrence() == $start)
					$data[] = $jsEvt;
				while (($next = $freq->nextOccurrence($start)) > 0 ) {
					if (!$next or $count >= 1000) break;
					$count++;
					$start = $next;
					$jsEvt["start"] = $start;
					$jsEvt["end"] = $start + $ev->getDuration()-1;

					$data[] = $jsEvt;
				}
			} else
				$data[] = $jsEvt;

		}
		
		PT_nextpiratentreff::$data[$calfile] = $data;
	}
	
	static public function getcaldata($calfile) {
		if (!PT_nextpiratentreff::$data[$calfile])
			PT_nextpiratentreff::parsecalfile($calfile);
		
		return PT_nextpiratentreff::$data[$calfile];
	}
	
	static public function shortcode($atts) {
		$options = get_option("piratentools-npt");
		$id = $atts['id'];	
		$searchstring = $options['content'][$id]['searchstring'];
		$calurl = $options['content'][$id]['calurl'];
		$offset = $options['content'][$id]['offset'];
		$remove = $atts['remove'];
		
		if (PT_nextpiratentreff::$found[$id]) {
			$event0 = PT_nextpiratentreff::$found[$id];
		} else {
			$data = PT_nextpiratentreff::getcaldata($calurl);
			foreach ($data as $val) {
				if (strpos(" ".$val['title'], $searchstring) != 0 && $val['start'] > (time()-$offset) && ($val['start'] < $event0['start'] || !$event0) ) {
					$event0 = $val;
					PT_nextpiratentreff::$found[$id] = $event0;
				}
			}
		}

	print_r($event0);
		$e_title = $event0['title'];
		$e_title2 = trim(str_replace($remove, "", $e_title));
		if ($event0) {
			$string = $atts['output'];
			$string = str_replace("%TITEL2%", $e_title2, $string);
			$string = str_replace("%TITEL%", $e_title, $string);
			$string = preg_replace('/(\{(.*?)})/e', 'date_i18n("$2", $event0["start"])', $string);
			//$string = strtr($string, $trans); 
			return $string;
		} else {
			return $atts['else']."asd";
		}
	}
	
	static public function piratentools_npt_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		$options = get_option("piratentools-npt");
		
		if ($_POST['pt-npt-action'] == "add") {
			if (!$options["nextid"]) $options["nextid"] = 1;
			$nextid = $options["nextid"]++;
			$upd_calurl = $_POST['pt-npt-add-calurl'];
			$upd_searchstring = $_POST['pt-npt-add-searchstring'];
			$upd_offset = $_POST['pt-npt-add-offset'];
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


}

add_shortcode( "pt-npt", array("PT_nextpiratentreff", "shortcode"));
?>