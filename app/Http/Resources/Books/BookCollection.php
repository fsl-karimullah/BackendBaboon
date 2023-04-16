<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(
            fn ($book) => [
                'id' => $book->id,
                'title' => $book->title,
                'authors' => $book->authors,
                'thumbnail' => $book->thumbnail ? asset('/storage/' . $book->thumbnail) : null,
            ]
        );
    } 
}
