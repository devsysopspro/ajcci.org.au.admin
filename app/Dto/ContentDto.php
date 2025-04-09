<?php


namespace App\Dto;


use App\Content;

class ContentDto implements \JsonSerializable
{
    protected $id;
    protected $title;
    protected $fields = [];
    public $orderColumnValue;

    /**
     * ContentDto constructor.
     * @param Content $content
     * @param string $orderField
     */
    public function __construct(Content $content, $orderColumn = "id")
    {
        $this->id = $content['id'];
        $this->title = $content['title'];

        foreach ($content['fields'] as $field) {
            $this->fields[$field['slug']] = $field['value'];
        }

        $this->orderColumnValue = $orderColumn == "id" ? $content->id : $this->fields[$orderColumn];

        foreach ($content['media'] as $media) {
            $this->fields[$media['collection_name']][] = $media->getFullUrl();
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'fields' => $this->fields
        ];
//        return json_encode($array, JSON_PRETTY_PRINT);
    }
}
