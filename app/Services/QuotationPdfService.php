<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\CompanySetting;
use App\Models\Quotation;
use Mpdf\Mpdf;

class QuotationPdfService
{
    public function render(Quotation $quotation): string
    {
        $quotation->loadMissing('items.product', 'customer', 'branch');
        $company = CompanySetting::current();
        $branches = Branch::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $fontDir = storage_path('fonts');
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'fontDir' => [$fontDir],
            'fontdata' => [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ] + (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'],
            'default_font' => 'solaimanlipi',
            'tempDir' => storage_path('app/mpdf'),
        ]);

        $mpdf->SetTitle('Quotation - ' . $quotation->quotation_no);
        $mpdf->SetAuthor($company->name ?? 'Admin');
        $mpdf->SetCreator('Accounting Software');

        $html = view('pdf.quotation', compact('quotation', 'company', 'branches'))->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }

    public function filename(Quotation $quotation): string
    {
        return $quotation->quotation_no . '.pdf';
    }
}
