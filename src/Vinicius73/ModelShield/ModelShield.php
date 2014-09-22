<?php namespace Vinicius73\ModelShield;

use Illuminate\Database\Eloquent\Model;
use Vinicius73\ModelShield\Traits;

abstract class ModelShield extends Model
{
   use Traits\Shield;
}