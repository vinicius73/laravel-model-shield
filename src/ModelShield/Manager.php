<?php

namespace Vinicius73\ModelShield;

use Illuminate\Support\Collection;

class Manager
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Collection
     */
    private $rules;

    public function __construct(array $config)
    {
        $this->path = array_get($config, 'path');
        $this->rules = new Collection();
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function loadRules($key)
    {
        if (!$this->rules->has($key)):
            $this->rules->put($key, $this->loadRulesFromFile($key));
        endif;

        return $this->rules->get($key);
    }

    /**
     * @param $key
     *
     * @return array
     */
    protected function loadRulesFromFile($key)
    {
        $file = $this->path.$key.'.php';

        /*
         * @TODO make Exception
         */

        return include $file;
    }

    /**
     * @param string $key
     * @param bool   $creating
     *
     * @return array
     */
    public function getRules($key, $creating = true)
    {
        switch ($creating):
            case true:
                return $this->getRulesCreating($key);
        break;
        case false:
                return $this->getRulesUpdating($key);
        break;
        default:
                return [];
        break;
        endswitch;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRulesDefault($key)
    {
        $rules = $this->loadRules($key);

        return array_get($rules, 'default', []);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRulesCreating($key)
    {
        $rules = $this->loadRules($key);

        $default = array_get($rules, 'default', []);
        $creating = array_get($rules, 'creating', []);

        return array_merge($default, $creating);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function getRulesUpdating($key)
    {
        $rules = $this->loadRules($key);

        $default = array_get($rules, 'default', []);
        $updating = array_get($rules, 'updating', []);

        return array_merge($default, $updating);
    }

   /**
    * @param $key
    *
    * @return array
    */
   public function getCustomMessages($key)
   {
       $rules = $this->loadRules($key);

       return array_get($rules, 'custom_messages', []);
   }

   /**
    * @param $key
    *
    * @return array
    */
   public function getAttributeNames($key)
   {
       $rules = $this->loadRules($key);

       return array_get($rules, 'attribute_names', []);
   }
}
