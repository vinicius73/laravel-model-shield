<?php

namespace Vinicius73\ModelShield\Traits;

trait ShieldSentry
{
    use Rules;
    use Messages;
    use ShieldValidator;

   /**
    * @param array $options
    *
    * @return bool
    */
   public function save(array $options = [])
   {
       $isValid = $this->isValid();

       if ($isValid):
         return parent::save($options);
       endif;

       return false;
   }
}
