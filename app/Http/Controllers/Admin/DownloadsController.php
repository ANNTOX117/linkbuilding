<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthoritySite;
use Illuminate\Http\Request;

class DownloadsController extends Controller {

    public function export_sites(Request $request) {
        $fileName = "Sites.csv";
        $sites    = AuthoritySite::all_items();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('URL',
                         'Subnet',
                         'PA',
                         'DA',
                         'TF',
                         'CF',
                         'DRE',
                         'Backlinks',
                         'Refering domains',
                         'Price',
                         'Special price'
        );

        $callback = function() use($sites, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sites as $site) {
                $values = array($site->url,
                                $site->subnet ?? '-',
                                $site->pa ?? '0',
                                $site->da ?? '0',
                                $site->tf ?? '0',
                                $site->cf ?? '0',
                                $site->dre ?? '0',
                                $site->backlinks ?? '-',
                                $site->refering_domains ?? '0',
                                $site->price ?? '0.00',
                                $site->price_special ?? '0.00'
                            );
                fputcsv($file, $values);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
