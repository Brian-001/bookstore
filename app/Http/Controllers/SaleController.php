<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.book_id' => 'required|exists:books,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $sale = Sale::create([
                'invoice_number' => 'INV-' . time(),
                'customer_name' => $validated['customer_name'],
                'total' => 0,
                'sale_date' => now(),
                'user_id' => auth()->id(),
            ]);
            $total = 0;
            foreach($validated['items'] as $item) {
                $book = Book::findOrFail($item['book_id']);
                if ($book->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$book->title}");
                }

                $sale->items()->create([
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                    'price' => $book->price * $item['quantity'],
                ]);

                $book->decrement('stock', $item['quantity']);
                $total += $book->price * $item['quantity'];
            }

            $sale->update(['total' => $total]);

            return request()->wantsJson()
                ? response()->json($sale->load('items.book'), 201)
                : redirect()->route('sales.index')->with('success', 'Sale recorded');
        });

    }

    public function generateInvoice(Sale $sale)
    {
        $sale->load('items.book');
        $pdf = Pdf::loadview('invoices.pdf', ['sale' =>$sale]);
        return $pdf->download("invoice_{$sale->invoice_number}.pdf");
    }
}
