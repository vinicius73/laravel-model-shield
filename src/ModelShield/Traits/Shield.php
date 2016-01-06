<?php

namespace Vinicius73\ModelShield\Traits;

trait Shield
{
    use Rules;
    use Messages;
    use ShieldValidator;

   /**
    * @param array $options
    * @param array $customRules
    *
    * @return bool
    */
   public function save(array $options = [], array $customRules = [])
   {
       $isValid = $this->isValid($customRules);

       if ($isValid):
         return parent::save($options);
       endif;

       return false;
   }

   /**
    * @param array $options
    *
    * @return mixed
    */
   public function forceSave(array $options = [])
   {
       return parent::save($options);
   }
}
