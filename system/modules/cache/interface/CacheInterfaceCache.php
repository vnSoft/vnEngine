<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');
/**
 * Description of CacheInterfaceCache
 *
 */
class CacheInterfaceCache {
    private $m_cacheModel;
    
    public function __construct() {
         $this->m_cacheModel = new CacheModelCache();
    }   
    
    public function add($sKey, $value, $iTime = 0) {
        if($iTime == 0)
            $iTime = Cache::config('time');
        
        $this->m_cacheModel->add($sKey, $value, $iTime);
    }
    
    public function get($sKey) {
        return $this->m_cacheModel->get($sKey);
    }
    
    public function delete($sKey) {
        $this->m_cacheModel->delete($sKey);
    }
    
    public function exists($sKey) {
        return $this->m_cacheModel->exists($sKey);
    }
    
    public function clear() {
        $this->m_cacheModel->clear();
    }
    
    public function createModelSpace($sModelName) {
        if(!$this->exists($sModelName.'-spaces'))
            $this->add($sModelName.'-spaces', array(), 3600);
    }
    
    public function isInModelSpace($sModelName, $sSpaceType, $ID) {
        $exists = false;
        $spaces = $this->get($sModelName.'-spaces');
        
        if(isset($spaces[$sSpaceType]) AND in_array($ID, $spaces[$sSpaceType]['ids']))
            $exists = true;
        
        $this->add($sModelName.'-spaces', $spaces, 3600);
        return $exists;
    }
    
    public function addToModelSpace($sModelName, $sSpaceType, $ID, $value) {
        $spaces = $this->get($sModelName.'-spaces');
        if(!isset($spaces[$sSpaceType])) {
            $spaces[$sSpaceType] = array();
            $spaces[$sSpaceType]['name'] = $sSpaceType;
            $spaces[$sSpaceType]['ids'] = array();
        }
        
        if(!in_array($ID, $spaces[$sSpaceType]['ids'])) 
            $spaces[$sSpaceType]['ids'] []= $ID;
        
       $this->add($sModelName.'-'.$sSpaceType.'-'.$ID, $value); 
       $this->add($sModelName.'-spaces', $spaces, 3600);
 
    }
    
    public function getFromModelSpace($sModelName, $sSpaceType, $ID) {
        return $this->get($sModelName.'-'.$sSpaceType.'-'.$ID);
    }
    
    public function clearModelSpace($sModelName, $sSpaceType = '') {
        $spaces = $this->get($sModelName.'-spaces');
        if($sSpaceType == '') {
            foreach($spaces as $space) {
                foreach($space['ids'] as $id) 
                    $this->delete($sModelName.'-'.$spaces['name'].'-'.$id);
            }
        } else {
            foreach($spaces[$sSpaceType]['ids'] as $id) {
                $this->delete($sModelName.'-'.$spaces[$sSpaceType]['name'].'-'.$id);
            }
        }
        $this->add($sModelName.'-spaces', array(), 3600);
    }
}

?>
