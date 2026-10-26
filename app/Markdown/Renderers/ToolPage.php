<?php

namespace App\Markdown\Renderers;

class ToolPage extends SeoConfigPage
{
    protected function group(): string
    {
        return 'tools';
    }

    protected function prefix(): string
    {
        return '/tools/';
    }
}
