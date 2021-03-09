<?php

namespace App\Models\Mails;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class MailRepository
{
  /**
   * Función genérica para ejecutar el envio de un email
   * @param string $email, email al que se envia el correo
   * @param string $name, nombre de la persona que recibirá el mail
   * @param string $template, archivo blade en el cual renderiza el mail
   * @param array $data, información que se pasa al template del mail
   * @param string $subject, título del mail
   */
  public static function enviar(
    $email,
    $name,
    $template,
    $data,
    $subject
  ) {
    Mail::send($template, $data, function ($message) use ($email, $name, $subject) {
      $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
      $message->to($email, $name);
      $message->subject($subject);
      $message->priority(1);
    });
  }

  public static function verificarEmail($data, $email, $name, $tokenConfirmacion)
  {
    $uiURL = env('URL_UI');
    $data['urlConfirmacion'] = "{$uiURL}?token={$tokenConfirmacion}";

    $now = Carbon::now();

    try {
      self::enviar($email, $name, 'mail.auth.confirmar_email', $data, "CONFIRMAR CORREO {$now}");
    } catch (Exception $e) {
      ThrowException(
        Response::HTTP_INTERNAL_SERVER_ERROR,
        'No se puedo enviar el correo de confirmación',
        $e->getMessage()
      );
    }
  }
}
