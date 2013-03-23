<?php

if (!class_exists("PT_SG_iCalReader")) {
	require_once('ical/SG_iCal.php');
}

class PT_nextpiratentreff {

	static private $data = array();
	static private $found = array();
	
	static private function parsecalfile($calfile) {
		$ical = new PT_SG_iCalReader($calfile);
		$query = new PT_SG_iCal_Query();

		$evts = $ical->getEvents();


		$data = array();
		if (is_array($evts)) {
			foreach($evts as $id => $ev) {
				$jsEvt = array(
					"id" => ($id+1),
					"title" => $ev->getProperty('summary'),
					"location" => $ev->getLocation(),
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
		}
		PT_nextpiratentreff::$data[$calfile] = $data;
	}
	
	static public function getcaldata($calfile) {
		if (!PT_nextpiratentreff::$data[$calfile])
			PT_nextpiratentreff::parsecalfile($calfile);
		
		return PT_nextpiratentreff::$data[$calfile];
	}
	
	static public function shortcode($atts) {
		$event0 = null;
		$event1 = null;
		$event2 = null;
	
		$options = get_option("pt_nextpiratentreff");
		$id = $atts['id'];	
		if (!$options['content'][$id]) return;
		
		$skip = $atts['skip'];
		if (!is_numeric($skip) || $skip < 0) $skip = 0;
		
		$searchstring = $options['content'][$id]['searchstring'];
		$calurl = $options['content'][$id]['calurl'];
		$offset = $options['content'][$id]['offset']*60;
		$remove = $atts['remove'];
		
		if (PT_nextpiratentreff::$found[$id][$skip]) {
			$event0 = PT_nextpiratentreff::$found[$id][$skip];
		} else {
			$data = PT_nextpiratentreff::getcaldata($calurl);
			foreach ($data as $val) {
				if (strpos(" ".$val['title'], $searchstring) != 0 && $val['start'] > (time()-$offset)) {
					$temp0 = $val['start'];
                    if (!isset($event1[$temp0])) {
                        $event1[$temp0] = $val;
                    }
				}
			}
			if (is_array($event1)) {
				ksort($event1);
				$event2 = array_values($event1);
				$event0 = $event2[$skip];
				PT_nextpiratentreff::$found[$id][$skip] = $event0;
			}
		}
		$e_title = $event0['title'];
		$e_title2 = trim(str_replace($remove, "", $e_title));
		$e_location = $event0['location'];
		if ($event0) {
			$string = $atts['output'];
			$string = str_replace("%TITEL2%", $e_title2, $string);
			$string = str_replace("%TITEL%", $e_title, $string);
			$string = str_replace("%ORT%", $e_location, $string);
			$string = preg_replace('/(\{(.*?)})/e', 'date_i18n("$2", $event0["start"])', $string);
			//$string = strtr($string, $trans); 
			return $string;
		} else {
			return $atts['else'];
		}
	}
	
	static public function adminmenu() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		$options = get_option("pt_nextpiratentreff");
		
		if ($_POST['pt-npt-action'] == "add") {
			if (!$options["nextid"]) $options["nextid"] = 1;
			$nextid = $options["nextid"]++;
			$upd_calurl = $_POST['pt-npt-add-calurl'];
			$upd_searchstring = $_POST['pt-npt-add-searchstring'];
			$upd_offset = 0;
			if (is_numeric($_POST['pt-npt-add-offset'])) {
				$upd_offset = $_POST['pt-npt-add-offset'];
			}
			$options["content"][$nextid]["calurl"] = $upd_calurl;
			$options["content"][$nextid]["searchstring"] = $upd_searchstring;
			$options["content"][$nextid]["offset"] = $upd_offset;
			update_option("pt_nextpiratentreff", $options);
		}
		
		if ($_POST['pt-npt-action'] == "del") {
			$del_ids = $_POST['pt-npt-del'];
			
			foreach ($del_ids as $key => $val) {
				if ($val == 1) {
					unset($options["content"][$key]);
				}
			}
			update_option("pt_nextpiratentreff", $options);
		}
		
		include('adminmenu.php');
	}


}

add_shortcode( "pt-npt", array("PT_nextpiratentreff", "shortcode"));
?>