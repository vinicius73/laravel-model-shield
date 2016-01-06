<?php

namespace Vinicius73\ModelShield\Traits;

use App;

trait Rules
{
    /**
    * @var array
    */
   private $rules;
   /**
    * @var array
    */
   private $customMessages;
   /**
    * @var array
    */
   private $attributesNames;

   /**
    * Provides validation rules.
    *
    * @return array
    */
   public function getRules()
   {
       if (!is_array($this->rules)):
         $manager = App::make('shield');
       $key = $this->getRulesKey();

       $this->rules = ($this->exists) ? $manager->getRulesUpdating($key) : $manager->getRulesCreating($key);
       endif;

       return $this->rules;
   }

   /**
    * @return array
    */
   public function getCustomMessages()
   {
       if (!is_array($this->customMessages)):
         $manager = App::make('shield');
       $key = $this->getRulesKey();

       $this->customMessages = $manager->getCustomMessages($key);
       endif;

       return $this->customMessages;
   }

   /**
    * @return array
    */
   public function getAttributeNames()
   {
       if (!is_array($this->attributesNames)):
         $manager = App::make('shield');
       $key = $this->getRulesKey();

       $this->attributesNames = $manager->getAttributeNames($key);
       endif;

       return $this->attributesNames;
   }

   /**
    * @return mixed
    */
   protected function getRulesKey()
   {
       if (isset($this->_rules_key)):
         return $this->_rules_key;
       endif;

       return $this->getTable();
   }
}
