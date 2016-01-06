<?php

namespace Vinicius73\ModelShield\Traits;

use Illuminate\Support\MessageBag;

trait Messages
{
    /**
    * @var \Illuminate\Support\MessageBag
    */
   protected $validationErrors;

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
}
