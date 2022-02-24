<?php
namespace App\Services;
use PDF as DomPDF;
use App\Models\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Pdf
{
  /**
   * Create a pdf
   * 
   * @param Application $application
   * @return Array
   */

  public function create($application)
  {
    $this->viewData['data'] = [];
    $pdf = DomPDF::loadView('pdf.letter.rejection', $this->viewData);
    $file = 'aporta-stiftung-gesuch-absage-' . date('dmY', time()) . '-' . \Str::random(12) . '.pdf';

    if (!File::isDirectory(storage_path('app/public/temp')))
    {
      File::makeDirectory(storage_path('app/public/temp'), 0775, true, true);
    }

    $pdf->save(public_path() . '/storage/temp/' . $file);

    return [
      'path' => public_path() . '/storage/temp/' . $file,
      'name' => $file
    ];
  }
}