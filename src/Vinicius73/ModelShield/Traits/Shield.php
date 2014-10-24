<?php namespace Vinicius73\ModelShield\Traits;

trait Shield
{
   use Rules;
   use Messages;
   use ShieldValidator;

   /**
    * @param array $customRules
    *
    * @return bool
    */
   public function isValid(array $customRules = array())
   {
      return (boolean)$this->validate($customRules);
   }

   /**
    * @param array $options
    * @param array $customRules
    *
    * @return bool
    */
   public function save(array $options = array(), array $customRules = array())
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
   public function forceSave(array $options = array())
   {
      $this->validate();

      return parent::save($options);
   }
}
