<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $books = Book::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
            })
            ->paginate(10);

        //Return JSON for API or Inertia for frontend
        if($request->wantsJson()) {
            return response()->json($books);
        }

        return Inertia::render('Books/Index', ['books' => $books]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:13',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create($validated);

        return $request->wantsJson()
        ? response()->json($book, 201)
        : redirect()->route('books.index')->with('success', 'Book created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return request()->wantsJson()
        ? response()->json($book->load('category'))
        : Inertia::render('Books/Show', ['book' => $book->load('category')]);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
        $validated =$request->validate([
            'title' => 'requiered|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn' .$book->id . '|max:13',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($validated);

        return $request->wantsJson()
            ? response()->json($book)
            : redirect()->route('books.index')->with('success', 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $book->delete();

        return request()->wantsJson()
            ? response()->json(null, 204)
            : redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
}
