<?php

namespace App\Http\Controllers;

use App\Http\Resources\Books\BookCollection;
use App\Http\Resources\Books\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query();
        if ($request->query('categoryId')) {
            $books->where('category_id', $request->query('categoryId'));
        }

        return new BookCollection($books->paginate(10));
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }
}
