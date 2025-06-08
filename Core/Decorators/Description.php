<?php

namespace Core\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Description
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
