<?php

namespace App\Inspections;

class InvalidKeywords
{
    protected $keywords = [
            'tmd'
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('敏感词汇？');
            }
        }
    }
}
