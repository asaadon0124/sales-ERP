<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalesOrder;
use App\Models\AdminSitting;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use PDF;
use App\Http\Controllers\Controller;

class PDFController extends Controller
{
    // public function downloadInvoice($id, $type)
    // {
    //      $settings = AdminSitting::where('company_code', auth()->user()->company_code)->first();

    //     if ($type === 'sales')
    //     {
    //         $order = SalesOrder::with('adminCreate','customer','servant','order_detailes')->where('auto_serial', $id)->firstOrFail();
    //     } else
    //     {
    //         $order = PurchaseOrder::with('adminCreate','supplier','store','order_detailes')->where('auto_serial', $id)->firstOrFail();
    //     }

    //     $pdf = PDF::loadView('BackEnd.Reborts.Items.invoice_pdf',
    //     [
    //         'order'             => $order,
    //         'admin_sittings'    => $settings,
    //     ]);

    //     return $pdf->download("invoice_{$id}.pdf");
    // }



    public function downloadInvoice($id,$type)
    {

        $settings = AdminSitting::where('company_code', auth()->user()->company_code)->first();

       if ($type === 'sales')
        {
            $order = SalesOrder::with('adminCreate','customer','servant','order_detailes')->where('auto_serial', $id)->firstOrFail();
        } else
        {
            $order = PurchaseOrder::with('adminCreate','supplier','store','order_detailes')->where('auto_serial', $id)->firstOrFail();
        }
        // dd('dsd');
        $pdf = PDF::loadView('BackEnd.Reborts.Items.invoice_pdf', [
            'order' => $order,
            'admin_sittings' => $settings,
        ]);

        return $pdf->download("invoice_{$id}.pdf");
    }

}
