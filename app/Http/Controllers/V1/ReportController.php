<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReportController extends Controller
{
    //
    public function sales(Request $request)
    {
        $period = $request->input('period', 'daily');
        $sales = Sale::with('items.book')
            ->whereBetween('sale_date', $this->getDateRange($period))
            ->get();

        if($request->input('format') === 'pdf'){
            $pdf = Pdf::loadView('reports.sales', ['sales' => $sales, 'period' => $period]);
            return $pdf->download("sales_report_{$period}_" . now()->format('Ymd') . ".pdf");
        }

        if ($request->input('format') === 'excel') {
            return Spreadsheet::download(new salesExport($sales), "sales_report_{$period}_" . now()->format('Ymd') . ".xlsx");
        }

        return Inertia::render('Reports/Sales', ['sales' => $sales, 'period' => $period]);
    }

    public function getDateRange($period)
    {
        $now = now();
        return match ($period) {
            'daily' => [$now->startOfDay(), $now->endOfDay()],
            'weekly' => [$now->startOfWeek(), $now->endOfWeek()],
            'monthly' => [$now->startOfMonth(), $now->endOfMonth()],
            default => [$now->startOfDay(), $now->endOfDay()],
        };
    }
}
