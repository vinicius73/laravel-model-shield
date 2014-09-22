<?php namespace Vinicius73\ModelShield\Traits;

use Illuminate\Support\MessageBag;
use Validator;

trait Shield
{
   /**
    * @var \Illuminate\Support\MessageBag
    */
   protected $validationErrors;

   /**
    * @return bool
    */
   public function isValid()
   {
      return (boolean)$this->validate();
   }

   /**
    * Provides validation rules
    *
    * @return array
    */
   public function getRules()
   {
      $rulesUpdate = self::rulesUpdate();

      if ($this->exists and !empty($rulesUpdate)):
         return $rulesUpdate;
      endif;

      return self::rules();
   }

   /**
    * @param array $options
    *
    * @return bool
    */
   public function save(array $options = array())
   {
      $isValid = $this->isValid();

      if ($isValid):
         return parent::save($options);
      endif;

      return false;
   }

   /**
    * Validates the model
    *
    * @return bool
    */
   protected function validate()
   {
      $rules = $this->getRules();
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
    * @return array
    */
   public static function rules()
   {
      return (isset(self::$rules)) ? self::$rules : [];
   }

   /**
    * @return array
    */
   public static function rulesUpdate()
   {
      return (isset(self::$rulesUpdate)) ? self::$rulesUpdate : [];
   }
}