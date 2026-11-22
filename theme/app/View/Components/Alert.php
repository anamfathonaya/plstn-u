<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    /** Pemetaan jenis pesan ke kelas token (light-only). */
    protected const CLASSES = [
        'success' => 'bg-accent text-on-inverse',
        'warning' => 'bg-live text-on-inverse',
        'caution' => 'bg-inverse text-on-inverse',
        'info' => 'bg-surface text-primary border border-subtle',
    ];

    public string $class;

    public function __construct(public string $type = 'info', public ?string $message = null)
    {
        $this->class = self::CLASSES[$type] ?? self::CLASSES['info'];
    }

    public function render(): View
    {
        return view('components.alert');
    }
}
