<?php

namespace App\Features\Tasks\Components;

use App\Features\Tasks\Models\TaskStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProgressBar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public TaskStatus $status) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.progress-bar', ['status' => $this->status]);
    }
}
