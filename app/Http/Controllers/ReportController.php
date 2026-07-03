<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman filter & rekapitulasi penjualan (Admin)
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Ambil data penjualan khusus yang sudah dikonfirmasi komplit, dengan relasi yang diperlukan.
        // whereBetween by default menggunakan pdo parameter binding untuk menjaga sistem dari SQL Injection.
        $sales = Sale::with(['user', 'details.product'])
            ->where('status', 'delivered')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->latest()
            ->get();

        $totalRevenue = $sales->sum('total_amount');

        return view('admin.reports.index', compact('sales', 'startDate', 'endDate', 'totalRevenue'));
    }

    /**
     * Ekspor data ke bentuk dokumen PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $sales = Sale::with(['user', 'details.product'])
            ->where('status', 'delivered')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->oldest() // Sortir dari tanggal paling tua ke baru saat direkap
            ->get();

        $totalRevenue = $sales->sum('total_amount');

        // Panggil layout PDF khusus tanpa navbar/sidebar
        $pdf = Pdf::loadView('admin.reports.pdf', compact('sales', 'startDate', 'endDate', 'totalRevenue'));

        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Laporan-Penjualan-' . Carbon::parse($startDate)->format('dM') . '-' . Carbon::parse($endDate)->format('dMY') . '.pdf';

        return $pdf->stream($fileName);
    }
}
