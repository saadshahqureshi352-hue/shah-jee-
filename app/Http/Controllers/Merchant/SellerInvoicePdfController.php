<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\SellerInvoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facades\Pdf;

class SellerInvoicePdfController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPdf(Request $request, SellerInvoice $invoice)
    {
        // Scope to merchant
        abort_unless((int) $invoice->user_id === (int) $request->user()->id, 403);

        // Minimal printable view (you can extend later)
        $pdf = Pdf::loadView('pdf.merchant.seller-invoice', [
            'invoice' => $invoice,
        ])->setPaper('a4', 'portrait');

        $fileSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $invoice->invoice_number);
        return $pdf->download($fileSafe.'.pdf');
    }
}

