<?php

namespace App\Markdown\Renderers;

class StackPage extends SeoConfigPage
{
    protected function group(): string
    {
        return 'stacks';
    }

    protected function prefix(): string
    {
        return '/for/';
    }
}
