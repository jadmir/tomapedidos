<?php

namespace App\Models\Files;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
  public static function saveFile($disk, $folder, $file)
  {
    try {
      $nameWithFolder = Storage::disk($disk)->putFile($folder, ($file));
    } catch (Exception $e) {
      ThrowException(Response::HTTP_INTERNAL_SERVER_ERROR, 'errorcito ven a milado', $e->getMessage());
    }

    $name = str_replace($folder, '', $nameWithFolder);
    $name = str_replace('/', '', $name);

    return [$nameWithFolder, $name];
  }

  public static function getURL($disk, $nameWithFolder)
  {
    try {
      return  Storage::disk($disk)->url("{$nameWithFolder}");
    } catch (Exception $e) {
      ThrowException(
        Response::HTTP_INTERNAL_SERVER_ERROR,
        'Error al obtener URL del archivo',
        $e->getMessage()
      );
    }
  }

  public static function deleteFile($disk, $name)
  {
    try {
      return Storage::disk($disk)->delete($name);
    } catch (Exception $e) {
      ThrowException(Response::HTTP_INTERNAL_SERVER_ERROR, 'errorcito ven a milado', $e->getMessage());
    }
  }
}
