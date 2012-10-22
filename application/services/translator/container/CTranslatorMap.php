<?php

defined('DOCROOT') OR die('Brak bezpośredniego dostępu do pliku!');

class CTranslatorMap extends Container{
    
    private $m_exactRoutes = array();
    private $m_routes = array();


    public function addRoute($sPath, $sRoute) {
        $this->m_routes[$sPath] = $sRoute;
    }
    
    public function addExactRoute($sPath, $sRoute) {
        $this->m_exactRoutes[$sPath] = $sRoute;
    }
    
    public function processExactRoute($sRequestPath) {
        if(isset($this->m_exactRoutes[$sRequestPath]))
            return $this->getRequest($this->m_exactRoutes[$sRequestPath]);
        else
            return false;
    }
    
    public function processRoute() {
        return false;
    }
    
    
    
    private function getRequest($sRoute) {
        $parameters = explode('/', trim($sRoute, '/'));
        $sModule = $sController = $sAction = '';
        
        if(!empty($parameters[0]))
            $sModule = $parameters[0];
        if(!empty($parameters[1]))
            $sController = $parameters[1];
        if(!empty($parameters[2]))
            $sAction = $parameters[2];

        array_shift($parameters);
        array_shift($parameters);
        array_shift($parameters);
        
        $request = new Request($sModule, $sController, $sAction);
        $parameters = array_chunk($parameters, 2);
        foreach ($parameters as $param) {
            if (isset($param[1]))
                $request->setParam($param[0], $param[1]);
        }
        
        return $request;
    }
}

?>
