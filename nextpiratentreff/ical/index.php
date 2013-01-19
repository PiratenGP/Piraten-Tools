 <?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 include('SG_iCal.php');
function dump_t($x) {
	echo "<pre>".print_r($x,true)."</pre>";
}
$ICS = "https://www.google.com/calendar/ical/i96m7pkltimlcuabnslnpteb60%40group.calendar.google.com/public/basic.ics";
//echo dump_t(file_get_contents($ICS));

$ical = new SG_iCalReader($ICS);
$query = new SG_iCal_Query();

$evts = $ical->getEvents();
//$evts = $query->Between($ical,strtotime('20100901'),strtotime('20101131'));


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
foreach ($data as $val) {
	if ($val['title'] == "Piratentreff Göppingen" && $val['start'] > time() && ($val['start'] < $n_gp || !$n_gp) ) {
		$n_gp = $val['start'];
	}



	if ($val['title'] == "Piratentreff Geislingen" && $val['start'] > time() && ($val['start'] < $n_gs || !$n_gs) ) {
		$n_gs = $val['start'];
	}
}
echo date("d.m.Y", $n_gp)."<br />";
echo date("d.m.Y", $n_gs);

 ?>