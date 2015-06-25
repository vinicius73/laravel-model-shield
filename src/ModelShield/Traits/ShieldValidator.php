<?php namespace Vinicius73\ModelShield\Traits;

use Validator;

trait ShieldValidator
{
   /**
    * @var \Illuminate\Validation\Validator
    */
   private $validator;

   /**
    * @param array $customRules
    *
    * @return bool
    */
   public function isValid(array $customRules = array())
   {
      return (boolean)$this->runValidation($customRules);
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
   protected function runValidation(array $customRules = array(), array $customMessages = array(), array $attributeNames = array())
   {
      $rules          = (empty($customRules)) ? $this->getRules() : $customRules;
      $messages       = (empty($customMessages)) ? $this->getCustomMessages() : $customMessages;
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
    * Prepare the attributes of the model before being validated
    *
    * @return array
    */
   protected function prepareAttributes()
   {
      return $this->getAttributes();
   }
}
