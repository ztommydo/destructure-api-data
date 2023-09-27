<?php

declare(strict_types=1);

namespace TommyDo\DestructureApiData;

use ErrorException;

class DestructureApiData
{
    /**
     * The provided structure of new array that want to transform to
     * Type: Array
     * 
     * @key: API's key
     * @value: return array key
     * 
     * Ex:
     *  ['films' => 'Starwars']
     *  $schema = ['films' => 'my_film']
     * Output:
     *  ['my_film' => 'Starwars']
     */
    private Array $schema;

    /**
     * Input the original data from API or somewhere
     */
    private Array $original_data;

    /**
     * Options for the process
     * @keep_origin: 0/1 - keep the origin data structure, and replace just matched schema's keys, otherwise, only matched key will be kept.
     */
    private Array $options;

    /**
     * The initialize function
     */
    public function __construct()
    {
        
    }

    /**
     * Main function
     * 
     * @return Array
     */
    public function destruct(): Array
    {
        $ori_data = $this->original_data;
        $schema   = $this->schema;

        $destructed_array = [];
        foreach ($ori_data as $key => $value) {
            if(array_key_exists($key, $schema)) {
                $destructed_array[$schema[$key]] = $value;
            } elseif(isset($this->options['keep_origin']) && $this->options['keep_origin'] === 1) {
                $destructed_array[$key] = $value;
            }
        }

        return $destructed_array;
    }

    /**
     * Get/Set methods
     */
    public function get($var_name, $options = [])
    {
        return $this->$var_name;
    }

    public function set($var_name, $value, $options = []): void
    {
        $this->$var_name = $value;
    }

    public function setOption($key_name, $value): void
    {
        $this->options[$key_name] = $value;
    }
}
