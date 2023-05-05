<?php

namespace App\Http\Resources\Books;

use App\Models\Bookmark;
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
            'is_bookmarked' => Bookmark::where('book_id', $this->id)
                ->where('user_id', auth()->user()->id)->count() ? true : false,
            'thumbnail' => $this->thumbnail ? asset('/storage/'.$this->thumbnail) : null,
            'pdf_preview' => $this->pdf_file_preview ? asset('/storage/'.$this->pdf_file_preview) : null,
            'pdf_full' => $this->pdf_file ? asset('/storage/'.$this->pdf_file) : null,
        ];
    }
}
