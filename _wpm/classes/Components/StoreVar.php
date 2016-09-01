<?php
namespace Wpm\Components;

use Wpm\Components\Interfaces\StoreInterface;

class StoreVar implements StoreInterface
{
    protected $wpmContainerVarKey = 'wpmVarStore';
    
    /**
     * Checks if key exists in store variables and returns boolean result.
     * 
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        if ($this->get($key)) return true;
        
        return false;
    }


    public function all()
    {
        global $wpmContainer;
        
        if (isset($wpmContainer[ $this->wpmContainerVarKey ])) {
            return $wpmContainer[ $this->wpmContainerVarKey ];
        }

        return [];
    }
    
    
    /**
     * Returns the value of a variable stored for a given key.
     * 
     * @param $key
     * @return null
     */
    public function get($key)
    {
        global $wpmContainer;
        
        if (isset($wpmContainer[ $this->wpmContainerVarKey ][ $key ])) {
            return $wpmContainer[ $this->wpmContainerVarKey ][ $key ];
        }
        
        return null;
    }
    
    
    /**
     * Stores a value to a given key in the variable store.
     * 
     * @param $key
     * @param null $value
     */
    public function set($key, $value = null)
    {
        global $wpmContainer;
        $wpmContainer[ $this->wpmContainerVarKey ][ $key ] = $value;
    }
    
    
    /**
     * Removes the key from the variable store.
     * 
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        global $wpmContainer;
        if ($this->has($key)) {
            unset($wpmContainer[ $this->wpmContainerVarKey ][ $key ]);
            
            return true;
        }
        
        return false;
    }
}
