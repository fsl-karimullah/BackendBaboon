<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource 
{
    public function toArray($request)
    { 
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'book' => $this->book->title,
            
        ];
    }
}
 