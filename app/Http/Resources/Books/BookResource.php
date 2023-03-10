<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'category' => $this->category->name,
            'title' => $this->title,
            'authors' => $this->authors,
            'description' => $this->description,
            'publisher' => $this->publisher,
            'publishedDate' => $this->published_date,
            'pageCount' => $this->page_count,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
