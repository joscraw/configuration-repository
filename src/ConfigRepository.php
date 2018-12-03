<?php

namespace Coalition;

class ConfigRepository implements \ArrayAccess
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * ConfigRepository Constructor
     * @param array $config
     */
    public function __construct($config = [])
    {
       foreach($config as $key => $value) {
           $this->set($key, $value);
       }
    }

    /**
     * Determine whether the config array contains the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->config[$key]) ? true : false;
    }

    /**
     * Set a value on the config array
     *
     * @param string $key
     * @param mixed  $value
     * @return \Coalition\ConfigRepository
     */
    public function set($key, $value)
    {

        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Get an item from the config array
     *
     * If the key does not exist the default
     * value should be returned
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    /**
     * Remove an item from the config array
     *
     * @param string $key
     * @return \Coalition\ConfigRepository
     */
    public function remove($key)
    {
        if(isset($this->config[$key])) {
            unset($this->config[$key]);
        }

        return $this;
    }

    /**
     * Load config items from a file or an array of files
     *
     * The file name should be the config key and the value
     * should be the return value from the file
     * 
     * @param array|string The full path to the files $files
     * @return void
     */
    public function load($files)
    {
        $files = is_array($files) ? $files : [$files];

        foreach($files as $file) {
            $basename = basename($file,".php");
            $configs = include($file);

            $this->set($basename, $configs);
        }
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}