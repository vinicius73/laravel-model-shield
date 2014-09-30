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
    * @var \Illuminate\Validation\Validator
    */
   private $validator;
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
    * @param array $customMessages
    * @param array $attributeNames
    *
    * @return bool
    */
   protected function validate(array $customRules = array(), array $customMessages = array(), array $attributeNames = array())
   {
      $rules = (empty($customRules)) ? $this->getRules() : $customRules;
      $messages = (empty($customMessages)) ? $this->getCustomMessages() : $customMessages;
      $attributeNames = (empty($attributeNames)) ? $this->getAttributeNames() : $attributeNames;

      $attributes = $this->prepareAttributes();

      $this->validator = $this->makeValidator($attributes, $rules, $messages);

      $this->validator->setAttributeNames($attributeNames);

      $success = $this->validator->passes();

      if (!$success):
         $this->setErrors($this->validator->errors());
      endif;

      return $success;
   }

   /**
    * @param array $attributes
    * @param array $rules
    * @param array $messages
    * @param array $customAttributes
    *
    * @return \Illuminate\Validation\Validator
    */
   protected function makeValidator(array $attributes, array $rules, array $messages = array(), array $customAttributes = array())
   {
      return Validator::make($attributes, $rules, $messages, $customAttributes);
   }

   /**
    * @return \Illuminate\Validation\Validator
    */
   public function getValidator()
   {
      if (is_null($this->validator)):
         $this->validate();
      endif;

      return $this->validator;
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