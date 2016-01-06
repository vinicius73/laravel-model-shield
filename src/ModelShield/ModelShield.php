<?php

namespace Vinicius73\ModelShield;

use Illuminate\Database\Eloquent\Model;

abstract class ModelShield extends Model
{
    use Traits\Shield;
}
