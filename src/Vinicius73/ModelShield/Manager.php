<?php namespace Vinicius73\ModelShield;

use Config;
use Illuminate\Support\Collection;

class Manager
{
    /**
     * @var Collection
     */
    private $config;
    private $rules;
    private $nameSpace = 'ShieldRules';

    public function __construct(array $config)
    {
        $this->config = Collection::make($config);
        $this->rules = Collection::make([]);

        $this->doConfiguration();
    }

    /**
     * init Configuration
     *
     * @return void
     */
    private function doConfiguration()
    {
        $path = $this->config->get('path');

        if (empty($path)):
            $path = app_path('models/rules'); // models/rules;
        endif;

        Config::addNamespace($this->nameSpace, $path);
    }


    /**
     * @param string $key
     *
     * @return array
     */
    public function loadRules($key)
    {
        if (!$this->rules->has($key)):
            $configName = $this->nameSpace . '::' . $key;

            $config = Config::get($configName, []);

            $this->rules->put($key, $config);
        endif;

        return $this->rules->get($key);
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
    * @return mixed
    */
   public function getCustomMessages($key)
   {
      $rules = $this->loadRules($key);

      return array_get($rules, 'custom_messages', []);
   }
}