<?php

if (!function_exists("file_get_html")) {
    include_once('simple_html_dom.php');
}

class PT_wikiimport {

	static public function shortcode($atts) {
		$id = $atts['id'];
		$else = $atts['else'];
		
		if ($atts['reload'] == 1) {
			PT_wikiimport::reload($id);
		}
		
		$options = get_option("pt_wikiimport");
		
		if ($options["content"][$id]["content"]) {
			return "<div class=\"ptwi-content\">\n".$options["content"][$id]["content"]."\n</div>";
		} else {
			return "<div class=\"ptwi-content\">\n".$else."\n</div>";
		}
	
	}
	

	static public function reload($id0 = 0) {
		if (isset($_GET['ptwi_reload']) || ($id0 != 0)) {
			if ($id0 == 0) {
				$id = $_GET['ptwi_reload'];
			} else {
				$id = $id0;
			}
			$options = get_option("pt_wikiimport");
			if (!$options["content"][$id]) return;
			if ($id0 == 0 && ($_GET['ptwi_kennwort'] != $options["content"][$id]["kennwort"])) return;
			
			$error = false;
			$url = $options['content'][$id]['pageurl'];
			$content = file_get_html($url);
			if (!$content) $error = true;
			
            $divfound = $content->find('div[id='.$options['content'][$id]['divid'].']');
            
			if ($divfound[0] != "" && !$error) {
            
                $txt = $divfound[0];
                $mainurl = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST)."/";
                $complurl = dirname($url."asd")."/";
                $txt = preg_replace("/(href|src)\=\"\//", "$1=\"$mainurl$2", $txt);
                $txt = preg_replace("/(href|src)\=\"([^(http)])/", "$1=\"$complurl$2", $txt);
                
                $options['content'][$id]['content'] = $txt;
                update_option("pt_wikiimport", $options);

			}
			
		}
		
	}

	static private function generatekennwort($length) {
		$passwd = "";
		$string = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789";
		
		mt_srand((double)microtime()*1000000);
		
		for ($i=1; $i<=$length; $i++) { 
			$passwd .= substr($string, mt_rand(0,strlen($string)-1), 1); 
		}
		return $passwd;
	}
	
	static public function adminmenu() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		$options = get_option("pt_wikiimport");
		
		if ($_POST['pt-wi-action'] == "add") {
			if (!$options["nextid"]) $options["nextid"] = 1;
			$nextid = $options["nextid"]++;
			$upd_pageurl = $_POST['pt-wi-add-pageurl'];
			$upd_divid = $_POST['pt-wi-add-divid'];
			$options["content"][$nextid]["pageurl"] = $upd_pageurl;
			$options["content"][$nextid]["divid"] = $upd_divid;
			$options["content"][$nextid]["kennwort"] = PT_wikiimport::generatekennwort(10);
			update_option("pt_wikiimport", $options);
		}
		
		if ($_POST['pt-wi-action'] == "del") {
			$del_ids = $_POST['pt-wi-del'];
			
			foreach ($del_ids as $key => $val) {
				if ($val == 1) {
					unset($options["content"][$key]);
				}
			}
			update_option("pt_wikiimport", $options);
		}
		
		include('adminmenu.php');
	}

	
}
add_shortcode( "pt-wi", array("PT_wikiimport", "shortcode"));
add_action('init', array("PT_wikiimport", 'reload'));

?>