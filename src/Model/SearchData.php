<?php

namespace App\Model;

class SearchData
{
    /** @var string  */
    public string $query = '';
    public function __toString(): string
    {
        return $this->query;

    }


}


