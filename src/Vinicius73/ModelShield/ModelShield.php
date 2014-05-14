<?php namespace Vinicius73\ModelShield;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Validator;

class ModelShield extends Model
{
	/**
	 * Rules for validation
	 *
	 * @var array
	 */
	public static $rules = array();
	/**
	 * Rules for validation on update
	 *
	 * @var array
	 */
	public static $rulesUpdate = array();
	/**
	 * @var \Illuminate\Support\MessageBag
	 */
	protected $validationErrors;

	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);
		$this->validationErrors = new MessageBag();
	}


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
		if ($this->exists and !empty(static::$rulesUpdate)):
			return static::$rulesUpdate;
		endif;

		return static::$rules;
	}


	public function save(array $options = array())
	{
		$isValid = $this->isValid();

		if ($isValid):
			return parent::save($options);
		endif;

		return FALSE;
	}

	/**
	 * Validates the model
	 *
	 * @return bool
	 */
	protected function validate()
	{
		$rules      = $this->getRules();
		$attributes = $this->prepareAttributes();
		$validator  = Validator::make($attributes, $rules);
		$success    = $validator->passes();

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

	public function getErrors()
	{
		return $this->validationErrors;
	}
}