<?php

namespace App\service;

use App\Service;
use App\Helpers\DevlessHelper as DLH;

trait service_downloader
{
    /**
     * Download service packages.
     *
     * @param   $filename
     *
     * @return
     *
     * @internal param $request
     */
    public function download_service_package($filename)
    {
        $file_path = DLH::get_file($filename);
        if ($file_path) {
            // Send Download
            return \Response::download($file_path, $filename)->deleteFileAfterSend(true);
        } else {
            DLH::flash('could not download files');
        }
    }
}
