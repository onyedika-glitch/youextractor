<?php

namespace App\Markdown\Renderers;

use App\Markdown\Renderer;

/**
 * Base for pages whose copy is hardcoded in a Blade view. Subclasses transcribe
 * that copy, so edit both when the page text changes.
 */
abstract class StaticPage implements Renderer
{
    public function section(): string
    {
        return 'core';
    }

    public function instances(): array
    {
        return [[]];
    }
}
