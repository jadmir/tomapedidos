<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
  protected $details;

  public function __construct($message, $code, $details = null)
  {
    parent::__construct($message, $code);
    $this->details = $details;
  }

  public function render()
  {
    $responseArray = [
      'message' => parent::getMessage(),
    ];

    if ($this->details && env('APP_DEBUG')) {
      $responseArray['error_details'] = $this->details;
    }

    return response($responseArray, parent::getCode());
  }
}
