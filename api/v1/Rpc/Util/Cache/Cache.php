<?php

namespace Rpc\Util\Cache;

/**
 *
 * @author user
 *        
 */
class Cache {
	
	private $cache;
	
	public function setParameters($adapter = 'config', $ttl = 3600) {
		
		if ($adapter === 'config') {
			$config = \Rpc\Util\Config\Config::getConfig();
			$adapter = $config['cache']['adapter'];
			$ttl = $config['cache']['ttl'];
		}
		
		switch ($adapter) {
			
			case 'apc' :
				try {
					$this->cache = new \Zend\Cache\Storage\Adapter\Apc ();
					$this->cache->getOptions ()->setTtl ( $ttl );
					
					$plugin = new \Zend\Cache\Storage\Plugin\ExceptionHandler ();
					$plugin->getOptions ()->setThrowExceptions ( false );
					$$this->cache->addPlugin ( $plugin );
					return true;
				} catch (\Exception $e) {
					return false;
				}

				break;

				case 'memcached' :
					try {
						$this->cache = new \Zend\Cache\Storage\Adapter\Memcached();
						$this->cache->getOptions ()->setTtl ( $ttl );
							
						$plugin = new \Zend\Cache\Storage\Plugin\ExceptionHandler ();
						$plugin->getOptions ()->setThrowExceptions ( false );
						$$this->cache->addPlugin ( $plugin );
						return true;
					} catch (\Exception $e) {
						return false;
					}
				
					break;
			default :
				try {
					$this->cache = new \Zend\Cache\Storage\Adapter\Filesystem ();
					$this->cache->getOptions ()->setTtl ( $ttl );
					$this->cache->getOptions ()->setCacheDir ( __DIR__ . DIRECTORY_SEPARATOR . 'cache' );
					
					$plugin = new \Zend\Cache\Storage\Plugin\ExceptionHandler ();
					$plugin->getOptions ()->setThrowExceptions ( false );
					$this->cache->addPlugin ( $plugin );
					return true;
				} catch (\Exception $e) {
					return false;
				}

				break;
		}
	} // function setParameters()
	
	public function getCache() {
		return $this->cache;
	}
	
	public function getItem($key, & $success = null, & $casToken = null) {
		return $this->cache->getItem ( $key, $success, $casToken );
	}
	public function getItems(array $keys) {
		return $this->cache->getItems ( $keys );
	}
	public function hasItem($key) {
		return $this->cache->hasItem ( $key );
	}
	public function hasItems(array $keys) {
		return $this->cache->hasItems ( $keys );
	}
	public function getMetadata($key) {
		return $this->cache->getMetadata ( $key );
	}
	public function getMetadatas(array $keys) {
		return $this->cache->getMetadatas ( $keys );
	}
	public function setItem($key, $value) {
		return $this->cache->setItem ( $key, $value );
	}
	public function setItems(array $keyValuePairs) {
		return $this->cache->setItems ( $keyValuePairs );
	}
	public function addItem($key, $value) {
		return $this->cache->addItem ( $key, $value );
	}
	public function addItems(array $keyValuePairs) {
		return $this->cache->addItems ( $keyValuePairs );
	}
	public function replaceItem($key, $value) {
		return $this->cache->replaceItem ( $key, $value );
	}
	public function replaceItems(array $keyValuePairs) {
		return $this->cache->replaceItems ( $keyValuePairs );
	}
	public function checkAndSetItem($token, $key, $value) {
		return $this->cache->checkAndSetItem ( $token, $key, $value );
	}
	public function touchItem($key) {
		return $this->cache->touchItem ( $key );
	}
	public function touchItems(array $keys) {
		return $this->cache->touchItems ( $keys );
	}
	public function removeItem($key) {
		return $this->cache->removeItem ( $key );
	}
	public function removeItems(array $keys) {
		return $this->cache->removeItems ( $keys );
	}
	public function incrementItem($key, $value) {
		return $this->cache->incrementItem ( $key, $value );
	}
	public function incrementItems(array $keyValuePairs) {
		return $this->cache->incrementItems ( $keyValuePairs );
	}
	public function decrementItem($key, $value) {
		return $this->cache->decrementItem ( $key, $value );
	}
	public function decrementItems(array $keyValuePairs) {
		return $this->cache->decrementItems ( $keyValuePairs );
	}
	public function getCapabilities() {
		return $this->cache->getCapabilities ();
	}
	public function getAvailableSpace() {
		return $this->cache->getAvailableSpace ();
	}
	public function getTotalSpace() {
		return $this->cache->getTotalSpace ();
	}
	public function clearByNamespace($namespace) {
		return $this->cache->clearByNamespace ( $namespace );
	}
	public function clearByPrefix($prefix) {
		return $this->cache->clearByPrefix ( $prefix );
	}
	public function clearExpired() {
		return $this->cache->clearExpired ();
	}
	public function flush() {
		return $this->cache->flush ();
	}
	public function getIterator() {
		return $this->getIterator ();
	}
	public function optimize() {
		return $this->optimize ();
	}
	public function setTags($key, $tags) {
		return $this->setTags ( $key, $tags );
	}
	public function getTags($key) {
		return $this->getTags ( $key );
	}
	public function clearByTags($tags, $disjunction = false) {
		return $this->clearByTags ( $tags, $disjunction );
	}
} // class Cache

?>