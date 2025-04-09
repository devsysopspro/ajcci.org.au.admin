<?php


namespace App\Component;


use App\Dto\ContentDto;
use Illuminate\Support\Collection;

class ContentEloquentToContentDtoConverter
{
    public function fromCollection(Collection $contents, $orderColumn=null, $sort= 'asc')
    {
        $response = [];

        foreach ($contents as $content) {
            $response[] = new ContentDto($content, $orderColumn);
        }

        return $sort == 'asc' ? collect($response)->sortBy("orderColumnValue")->values() : collect($response)->sortByDesc("orderColumnValue")->values();
    }

    public function fromItem(Content $content) {
        return new ContentDto($content);
    }
}
