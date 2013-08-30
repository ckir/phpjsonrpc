<?php

namespace Local\Greek\Namedays;

/**
 * List of namedays for today, tomorrow and the day after tomorrow.
 */
class Namedays {
	
	/**
	 * Get a list of namedays for today, tomorrow and the day after tomorrow.
	 *
	 * @return Ambigous <multitype:, string>
	 * @throws Exception\RuntimeException
	 */
	public function getNamedays() {
		$cache = __DIR__ . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . date ( "Y-m-d", time () ) . ".res";
		
		if (file_exists ( $cache )) {
			$response = unserialize ( file_get_contents ( $cache ) );
			return $response;
		}
		
		$cache = \Zend\Cache\StorageFactory::factory ( array (
				'adapter' => array (
						'name' => 'filesystem',
						'options' => array (
								'cache_dir' => __DIR__ . '/cache',
								'ttl' => 100 
						) 
				),
				'plugins' => array (
						array (
								'name' => 'serializer',
								'options' => array () 
						) 
				) 
		) );
		
		\Zend\Feed\Reader\Reader::setCache ( $cache );
		\Zend\Feed\Reader\Reader::useHttpConditionalGet ();
		
		$response = array ();
		$dates = array ();
		
		$feed = \Zend\Feed\Reader\Reader::import ( 'http://www.eortologio.gr/rss/si_av_me_en.xml' );
		
		foreach ( $feed as $entry ) {
			$title = $entry->getTitle ();
			$title = preg_replace ( '/\(source : www.namedays.gr\)/', '', $title );
			$title = explode ( ":", $title );
			$title [0] = trim ( $title [0] );
			$title [1] = trim ( $title [1] );
			
			preg_match ( '/\w{1,2}?\/\w/', $title [0], $date );
			$fdate = $date [0] . "/" . date ( "Y", time () ) . " 00:00:00";
			$date = \DateTime::createFromFormat ( 'd/m/Y H:i:s', $fdate );
			
			$fnames = explode ( ",", $title [1] );
			for($i = 0; $i < count ( $fnames ); $i ++) {
				$fnames [$i] = trim ( $fnames [$i] );
			}
			
			if (count ( $fnames ) > 1) {
				$fnames = array_diff ( $fnames, array (
						"no widely known nameday" 
				) );
			}
			
			$response [$date->format ( "Y-m-d" )] ['en'] = $fnames;
			$dates [] = $date->format ( "Y-m-d" );
		}
		
		$feed = \Zend\Feed\Reader\Reader::import ( "http://www.eortologio.gr/rss/si_av_me_el.xml" );
		
		$j = 0;
		foreach ( $feed as $entry ) {
			$title = $entry->getTitle ();
			$title = preg_replace ( '/\(πηγή : www.eortologio.gr\)/', '', $title );
			$title = explode ( ":", $title );
			$title [0] = trim ( $title [0] );
			$title [1] = trim ( $title [1] );
			
			$fnames = explode ( ",", $title [1] );
			for($i = 0; $i < count ( $fnames ); $i ++) {
				$fnames [$i] = trim ( $fnames [$i] );
			}
			
			if (count ( $fnames ) > 1) {
				$fnames = array_diff ( $fnames, array (
						"δεν υπάρχει μια γιορτή πάρα πολύ γνωστή" 
				) );
			}
			
			$response [$dates [$j ++]] ['gr'] = $fnames;
		}
		
		file_put_contents ( __DIR__ . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . $dates [0] . ".res", serialize ( $response ) );
		return $response;
	} // function getNamedays
} // class Namedays

?>