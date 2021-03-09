<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ValidationException extends Exception
{
  protected $details;
  protected $field;

  public function __construct($message, $field, $rule, $details = null)
  {
    parent::__construct($message);
    $this->details = $details;
    $this->field = $field;
    $this->rule = $rule;
  }

  public function render()
  {
    $responseArray = [
      'message' => parent::getMessage(),
      'field' => $this->field,
      'rule' => $this->rule
    ];

    if ($this->details && env('APP_DEBUG')) {
      $responseArray['error_details'] = $this->details;
    }

    return response($responseArray, Response::HTTP_UNPROCESSABLE_ENTITY);
  }
}
