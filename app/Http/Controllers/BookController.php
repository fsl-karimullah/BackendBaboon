<?php

namespace App\Http\Controllers;

use App\Http\Resources\Books\BookCollection;
use App\Http\Resources\Books\BookmarkCollection;
use App\Http\Resources\Books\BookResource;
use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query();
        if ($request->query('category_id')) {
            $books->where('category_id', $request->query('category_id'));
        }

        if ($request->query('query')) {
            $books->where('title', 'like', '%'.$request->query('query').'%');
        }

        return new BookCollection($books->paginate(10));
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function bookmarkBook(Request $request, Book $book)
    {
        $user = $request->user();

        $isBookmarkExists = Bookmark::where([
            ['user_id', $user->id],
            ['book_id', $book->id],
        ])->count();

        if ($isBookmarkExists) {
            throw ValidationException::withMessages(
                ['books' => 'Buku telah di bookmark']
            );
        }

        $bookmark = Bookmark::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return $bookmark;
    }

    public function getAllBookmarks(Request $request)
    {
        $user = $request->user();

        return new BookmarkCollection($user->bookmarks);
    }

    public function removeBookmark(Bookmark $bookmark)
    {
        abort_if($bookmark->user->id != auth()->user()->id, 403);

        $bookmark->delete();

        return $bookmark;
    }
}
