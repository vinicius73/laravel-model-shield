<?php namespace Vinicius73\ModelShield\Traits;

use App;
use Illuminate\Support\MessageBag;
use Validator;

trait Shield
{
   /**
    * @var \Illuminate\Support\MessageBag
    */
   protected $validationErrors;
   /**
    * @var array
    */
   private $rules;

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
    * Provides validation rules
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

   /**
    * Validates the model
    *
    * @param array $customRules
    *
    * @return bool
    */
   protected function validate(array $customRules = array())
   {
      $rules = (empty($customRules)) ? $this->getRules() : $customRules;
      $attributes = $this->prepareAttributes();
      $validator = Validator::make($attributes, $rules);

      $success = $validator->passes();

      if (!$success):
         $this->setErrors($validator->errors());
      endif;

      return $success;
   }

   /**
    * Prepare the attributes of the model before being validated
    *
    * @return array
    */
   protected function prepareAttributes()
   {
      return $this->getAttributes();
   }

   /**
    * @param MessageBag $errors
    */
   protected function setErrors(MessageBag $errors)
   {
      $this->validationErrors = $errors;
   }


   /**
    * @return MessageBag
    */
   public function getErrors()
   {
      if (!is_object($this->validationErrors)):
         $this->validationErrors = new MessageBag();
      endif;

      return $this->validationErrors;
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