<?php

/**
 * Plugin Name: Googlomatic
 * Version: 1.0
 * Author: Technodigtiz.com
 * Author URI: https://technodigitz.com/
 * Description: Fetch and display Top 10 Results from Google Serp.
 * 
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/includes/options.php';

class Googlomatic {
	/** @var string The location of the plugin. Always with a trailing slash. */
	private $plugin_dir;
	/** @var string Path to a log file */
	private static $log_file_path;
	/** @var bool Is debug mode enabled? */
	private static $debug = false;
	/** @var string Only debug from this remote ip (useful i.e. in production to avoid overloading the debug file) */
	private static $debug_ip = '';
	/** Array list of citoes */
	private $cities = array(
		"upplands väsby"=>"upplands väsby","vallentuna"=>"vallentuna","Österåker"=>"Österåker","värmdö"=>"värmdö","järfälla"=>"järfälla","ekerö"=>"ekerö","huddinge"=>"huddinge","botkyrka"=>"botkyrka","salem"=>"salem","haninge"=>"haninge","tyresö"=>"tyresö","upplands-bro"=>"upplands-bro","nykvarn"=>"nykvarn","täby"=>"täby","danderyd"=>"danderyd","sollentuna"=>"sollentuna","stockholm"=>"stockholm","södertälje"=>"södertälje","nacka"=>"nacka","sundbyberg"=>"sundbyberg","solna"=>"solna","lidingö"=>"lidingö","vaxholm"=>"vaxholm","norrtälje"=>"norrtälje","sigtuna"=>"sigtuna","nynäshamn"=>"nynäshamn","håbo"=>"håbo","Älvkarleby"=>"Älvkarleby","knivsta"=>"knivsta","tierp"=>"tierp","uppsala"=>"uppsala","enköping"=>"enköping","Östhammar"=>"Östhammar","vingåker"=>"vingåker","gnesta"=>"gnesta","nyköping"=>"nyköping","oxelösund"=>"oxelösund","flen"=>"flen","katrineholm"=>"katrineholm","eskilstuna"=>"eskilstuna","strängnäs"=>"strängnäs","trosa"=>"trosa","Ödeshög"=>"Ödeshög","ydre"=>"ydre","kinda"=>"kinda","boxholm"=>"boxholm","Åtvidaberg"=>"Åtvidaberg","finspång"=>"finspång","valdemarsvik"=>"valdemarsvik","linköping"=>"linköping","norrköping"=>"norrköping","söderköping"=>"söderköping","motala"=>"motala","vadstena"=>"VcCU_Y86_eKU","mjölby"=>"mjölby","lekeberg"=>"lekeberg","laxå"=>"laxå","hallsberg"=>"hallsberg","degerfors"=>"degerfors","hällefors"=>"hällefors","ljusnarsberg"=>"ljusnarsberg","Örebro"=>"Örebro","kumla"=>"kumla","askersund"=>"askersund","karlskoga"=>"karlskoga","nora"=>"nora","lindesberg"=>"lindesberg","skinnskatteberg"=>"skinnskatteberg","surahammar"=>"surahammar","heby"=>"heby","kungsör"=>"kungsör","hallstahammar"=>"hallstahammar","norberg"=>"norberg","västerås"=>"västerås","sala"=>"sala","fagersta"=>"fagersta","köping"=>"köping","arboga"=>"arboga","olofström"=>"olofström","karlskrona"=>"karlskrona","ronneby"=>"ronneby","karlshamn"=>"karlshamn","sölvesborg"=>"sölvesborg","svalöv"=>"svalöv","staffanstorp"=>"staffanstorp","burlöv"=>"burlöv","vellinge"=>"vellinge","Östra göinge"=>"Östra göinge","Örkelljunga"=>"Örkelljunga","bjuv"=>"bjuv","kävlinge"=>"kävlinge","lomma"=>"lomma","svedala"=>"svedala","skurup"=>"skurup","sjöbo"=>"sjöbo","hörby"=>"hörby","höör"=>"höör","tomelilla"=>"tomelilla","bromölla"=>"bromölla","osby"=>"osby","perstorp"=>"perstorp","klippan"=>"klippan","Åstorp"=>"Åstorp","båstad"=>"båstad","malmö"=>"malmö","lund"=>"lund","landskrona"=>"landskrona","helsingborg"=>"helsingborg","höganäs"=>"höganäs","eslöv"=>"eslöv","ystad"=>"ystad","trelleborg"=>"trelleborg","kristianstad"=>"kristianstad","simrishamn"=>"simrishamn","Ängelholm"=>"Ängelholm","hässleholm"=>"hässleholm","kil"=>"kil","eda"=>"eda","torsby"=>"torsby","storfors"=>"storfors","hammarö"=>"hammarö","munkfors"=>"munkfors","forshaga"=>"forshaga","grums"=>"grums","Årjäng"=>"Årjäng","sunne"=>"sunne","karlstad"=>"karlstad","kristinehamn"=>"kristinehamn","filipstad"=>"filipstad","hagfors"=>"hagfors","arvika"=>"arvika","säffle"=>"säffle","vansbro"=>"vansbro","malung-sälen"=>"malung-sälen","gagnef"=>"gagnef","leksand"=>"leksand","rättvik"=>"rättvik","orsa"=>"orsa","Älvdalen"=>"Älvdalen","smedjebacken"=>"smedjebacken","mora"=>"mora","falun"=>"falun","borlänge"=>"borlänge","säter"=>"säter","hedemora"=>"hedemora","avesta"=>"avesta","ludvika"=>"ludvika","ockelbo"=>"ockelbo","hofors"=>"hofors","ovanåker"=>"ovanåker","nordanstig"=>"nordanstig","ljusdal"=>"ljusdal","gävle"=>"gävle","sandviken"=>"sandviken","söderhamn"=>"söderhamn","bollnäs"=>"bollnäs","hudiksvall"=>"hudiksvall","Ånge"=>"Ånge","timrå"=>"timrå","härnösand"=>"härnösand","sundsvall"=>"sundsvall","kramfors"=>"kramfors","sollefteå"=>"sollefteå","Örnsköldsvik"=>"Örnsköldsvik","ragunda"=>"ragunda","bräcke"=>"bräcke","krokom"=>"krokom","strömsund"=>"strömsund","Åre"=>"Åre","berg"=>"berg","härjedalen"=>"härjedalen","Östersund"=>"Östersund","nordmaling"=>"nordmaling","bjurholm"=>"bjurholm","vindeln"=>"vindeln","robertsfors"=>"robertsfors","norsjö"=>"norsjö","malå"=>"malå","storuman"=>"storuman","sorsele"=>"sorsele","dorotea"=>"dorotea","vännäs"=>"vännäs","vilhelmina"=>"vilhelmina","Åsele"=>"Åsele","umeå"=>"umeå","lycksele"=>"lycksele","skellefteå"=>"skellefteå","arvidsjaur"=>"arvidsjaur","arjeplog"=>"arjeplog","jokkmokk"=>"jokkmokk","Överkalix"=>"Överkalix","kalix"=>"kalix","Övertorneå"=>"Övertorneå","pajala"=>"pajala","gällivare"=>"gällivare","Älvsbyn"=>"Älvsbyn","luleå"=>"luleå","piteå"=>"piteå","boden"=>"boden","haparanda"=>"haparanda","kiruna"=>"kiruna","aneby"=>"aneby","gnosjö"=>"gnosjö","mullsjö"=>"mullsjö","habo"=>"habo","gislaved"=>"gislaved","vaggeryd"=>"vaggeryd","jönköping"=>"jönköping","nässjö"=>"nässjö","värnamo"=>"värnamo","sävsjö"=>"sävsjö","vetlanda"=>"vetlanda","eksjö"=>"eksjö","tranås"=>"tranås","uppvidinge"=>"uppvidinge","lessebo"=>"lessebo","tingsryd"=>"tingsryd","alvesta"=>"alvesta","Älmhult"=>"Älmhult","markaryd"=>"markaryd","växjö"=>"växjö","ljungby"=>"ljungby","högsby"=>"högsby","torsås"=>"torsås","mörbylånga"=>"mörbylånga","hultsfred"=>"hultsfred","mönsterås"=>"mönsterås","emmaboda"=>"emmaboda","kalmar"=>"kalmar","nybro"=>"nybro","oskarshamn"=>"oskarshamn","västervik"=>"västervik","vimmerby"=>"vimmerby","borgholm"=>"borgholm","gotland"=>"gotland","hylte"=>"hylte","halmstad"=>"halmstad","laholm"=>"laholm","falkenberg"=>"falkenberg","varberg"=>"varberg","kungsbacka"=>"kungsbacka","härryda"=>"härryda","partille"=>"partille","Öckerö"=>"Öckerö","stenungsund"=>"stenungsund","tjörn"=>"tjörn","orust"=>"orust","sotenäs"=>"sotenäs","munkedal"=>"munkedal","tanum"=>"tanum","dals-ed"=>"dals-ed","färgelanda"=>"färgelanda","ale"=>"ale","lerum"=>"lerum","vårgårda"=>"vårgårda","bollebygd"=>"bollebygd","grästorp"=>"grästorp","essunga"=>"essunga","karlsborg"=>"karlsborg","gullspång"=>"gullspång","tranemo"=>"tranemo","bengtsfors"=>"bengtsfors","mellerud"=>"mellerud","lilla edet"=>"lilla edet","mark"=>"mark","svenljunga"=>"svenljunga","herrljunga"=>"herrljunga","vara"=>"vara","götene"=>"götene","tibro"=>"tibro","töreboda"=>"töreboda","göteborg"=>"göteborg","mölndal"=>"mölndal","kungälv"=>"kungälv","lysekil"=>"lysekil","uddevalla"=>"uddevalla","strömstad"=>"strömstad","vänersborg"=>"vänersborg","trollhättan"=>"trollhättan","alingsås"=>"alingsås","borås"=>"borås","ulricehamn"=>"ulricehamn","Åmål"=>"Åmål","mariestad"=>"mariestad","lidköping"=>"lidköping","skara"=>"skara","skövde"=>"skövde","hjo"=>"hjo","tidaholm"=>"tidaholm","falköping"=>"falköping"
	);

	const PLUGIN_NAME = 'Googlomatic';
	const INCLUDES_PREFIX = __CLASS__;

	protected $routing_helper;
	/** @var Googlomatic_Filename_Helper */
	protected $filename_helper;

	/**
	 * Sets the plugin dir
	 *
	 * @version 1.2.0
	 * @since 1.0
	 */
	public function __construct() {
		$this->plugin_dir = trailingslashit( dirname( __FILE__ ) );
	}

	/**
	 * Initializes the plugin for every page load
	 *
	 * @return $this
	 *
	 * @version 1.1.4
	 * @since 1.0
	 */
	public function init() {
		Googlomatic::log('Initializing', __METHOD__);
		$this->enqueue_scripts();

		add_shortcode ('serp_list',array( $this, 'display_serp' ));
		return $this;
	}

	/**
	 * Add plugin shortcodes
	 *
	 *
	 * @version 1.1
	 * @since 1.0
	 */
	public function get_results($region_code) {

		$googlomatic_options = get_option( 'googlomatic_option_name' );
		$query = $googlomatic_options['search_term_0']; 
		$lang = $googlomatic_options['googlo_lang']; 
		$limit = $googlomatic_options['googlomatic_limit']; 
		
		$query_decoded = urldecode($query);
		
		$q = urlencode($query_decoded);

		if(strpos($q, '%20') !== false) {
			$q = str_replace('%20', '+', $query);
		}else{
	    	$q = str_replace(' ', '+', $query);
		}
		
		$seid = $googlomatic_options['googlomatic_seid']!=''?$googlomatic_options['googlomatic_seid']:'42b04e17d06b243c9'; 
		$api_key = $googlomatic_options['googlomatic_api']!=''?$googlomatic_options['googlomatic_api']:"AIzaSyD5_pRbtMiSl2gETN287jC1Gfa7ZxISTfk"; 
		$qstr = "&q=".$q;
		if($region_code!='sweden') $qstr .= "+".$region_code;
		$api_url = "https://seo.expediten.com/api.php?id=google-serp&site=envato.com&keyword=themeforest&country=US";	
				
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $api_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			//Only show errors while testing
			//echo "cURL Error #:" . $err;
			$responseObj = new stdClass();
		} else {
			//The API returns data in JSON format, so first convert that to an array of data objects
			$responseObj = json_decode($response);
			//print_r($responseObj);
		}
// 		echo "Response", "<pre>",print_r($responseObj),"</pre>";
		return $responseObj;
	
	}


	/**
	 * Add plugin shortcodes
	 *
	 *
	 * @version 1.1
	 * @since 1.0
	 */
	public function display_serp($atts = array()) {
		$region_code = "";

		if (isset($atts['city'])!="") {
			$region = $atts['city']!="sweden"?$atts['city']:'';
			if ($atts['city']=='sweden')
			$region_code = 'sweden';
		}
		else {
			global $post;
			$geoCity = geodir_location_get_current('city');
			$region = strtolower($geoCity);
		}


		if ($region_code!='sweden')
		if ($this->cities[$region]) 
			$region_code = $this->cities[$region];

		$serp_list = $this->get_results($region_code);
		
		if(is_array($serp_list->results))
		if(count($serp_list->results)>0):
			$html =  '<div class="serp"><div class="serp__container">';
				$counter = 1;
					foreach  ($serp_list->results as $serp) {
						$html .= '<div class="serp__item"><p class="serp__item-info"><a href="'.$serp->href.'" target="_blank">'.$serp->title.'</a></p>
						<div class="serp__item-info"><div class="serp__description serph-'.$counter.'">'.$serp->description.'</div></div></div>';
						$counter++;
					}
				$html .= '</div>';
			$html .= '</div>';
		endif;

		return $html;
	}

	/**
	 * Enqueue script files
	 *
	 *
	 * @version 1.1
	 * @since 1.0
	 */
	public function enqueue_scripts () {
		wp_enqueue_script( 'googlomatic_script', plugin_dir_url( __FILE__ ) . '/assets/googlomatic-script.js', ['jquery'], true );
		wp_enqueue_style('googlomatic_stylesheet', plugin_dir_url(__FILE__) . '/assets/googlomatic_style.css', '1.0.0');
	}

	/**
	 * On activation/install
	 *
	 * @return $this
	 *
	 * @version 1.1
	 * @since 1.0
	 */
	public static function install() {
		// Setup the install code
	}

	/**
	 * On deactivation/uninstall
	 *
	 * @return $this
	 *
	 * @version 1.0.1
	 * @since 1.0
	 */
	public static function uninstall() {
		// Can't flush rewrite rules again due to a wp bug
	}

	/**
	 * Get the absolute directory path of the plugin
	 *
	 * @param null|string $path
	 *
	 * @return string
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	public function get_dir( $path = null ) {
		return $path ? trailingslashit( $this->plugin_dir ) . $path : $this->plugin_dir;
	}

	/**
	 * Get the absolute url of the plugin
	 *
	 * @param null|string $path Any additional path to the plugin (i.e. "assets/css/style.css")
	 *
	 * @return string
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	public function get_url( $path = null ) {
		return plugins_url( $path, __FILE__ );
	}

	/**
	 * Require files from the include/ folder
	 *
	 * @param string $file_name The file name to require
	 *
	 * @return $this
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	public function load_lib_file( $file_name, $class_name = null ) {
		if ( is_null( $class_name ) || ! class_exists( $class_name ) ) {
			require_once $this->get_dir() . 'include/' . $file_name . '.php';
		}

		return $this;
	}
	/**
	 * Get path to the log file
	 *
	 * @return string
	 *
	 * @version 1.1.4
	 * @since 1.1.4
	 */
	protected static function get_log_file_path() {
		if ( ! self::$log_file_path ) {
			$uploads_dir = wp_upload_dir();
			self::$log_file_path = $uploads_dir['basedir'] . DIRECTORY_SEPARATOR . 'laimres_debug.log';
		}

		return self::$log_file_path;
	}

	/**
	 * @return bool
	 *
	 * @version 1.1.4
	 * @since 1.1.4
	 */
	public static function is_debug_enabled() {
		return self::$debug;
	}

	/**
	 * Enables logging to
	 *
	 * @var string $ip Only enable debugging from a certain ip. Useful i.e. in production to avoid overloading the debug file
	 *
	 * @version 1.3.2
	 * @since 1.1.4
	 */
	public static function enable_debug( $ip = '' ) {
		self::$debug = true;
		self::$debug_ip = $ip;
	}

	/**
	 * Disables logging
	 *
	 * @version 1.1.4
	 * @since 1.1.4
	 */
	public static function disable_debug() {
		self::$debug = false;
	}

	/**
	 * Save a post to the log file (if debug is enabled)
	 *
	 * @param string $message
	 * @param string|null $context I.e. __FILE__ or __METHOD__
	 *
	 * @return bool
	 *
	 * @version 1.3.2
	 * @since 1.1.4
	 */
	public static function log( $message, $context = null ) {
		global $wp;

		$debug_from_this_ip_only = ( ! empty( self::$debug_ip ) ) && self::$debug_ip == $_SERVER['REMOTE_ADDR'];

		if ( ! self::is_debug_enabled() || ! $debug_from_this_ip_only ) {
			return false;
		}
		$log_post = "[" . date( DATE_ATOM ) . "][{$_SERVER['REQUEST_URI']}]";

		if ( $context ) {
			$log_post .= " $context:";
		}

		$log_post .= " $message";

		$log_post .= "\n";
		file_put_contents( self::get_log_file_path(), $log_post, FILE_APPEND );

		return true;
	}
}

// Install/uninstall
register_activation_hook( __FILE__, array( 'Googlomatic', 'install' ) );
register_deactivation_hook( __FILE__, array( 'Googlomatic', 'uninstall' ) );

// Initialize global plugin
global $googlomatic;

$googlomatic = new Googlomatic();
$googlomatic->init();
