<?php

namespace Exfriend\Robots;

class ExtractedItem
{
    public $data = [];
    protected $rules = [];

    public function __construct( $data = null )
    {
        $this->data = $data;
    }

    public function valid()
    {
        return $this->validation()->passes();
    }

    public function validation()
    {
        return \Validator::make( $this->data, $this->rules );
    }

    public function errors()
    {
        return $this->validation()->errors()->all();
    }

    /*
     * Export
     */

    public function toEloquent( $modelName )
    {
        return new $modelName( $this->toArray() );
    }

    public function toCsv()
    {
        return $this->toArray();
    }

    /*
     * Getters
     */

    public function __get( $field )
    {
        return $this->data[ $field ];
    }

    public function toArray()
    {
        return $this->data;
    }

    public function __toString()
    {
        return json_encode( $this->toArray() );
    }


}
