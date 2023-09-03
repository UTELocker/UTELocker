<?php

namespace App\View\Components;

use App\Classes\Files;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Locker extends Component
{
    public $locker;
    public $image;
    /**
     * Create a new component instance.
     */
    public function __construct($locker)
    {
        $this->locker = $locker;
        if ($locker->image) {
            $this->image = Files::getImageUrl(
                $locker->image, 'client-locker',
                Files::CLIENT_UPLOAD_FOLDER
            );
        } else {
            $this->image = asset('images/default/lockerDefault.png');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.locker', [
            'locker' => $this->locker,
            'image' => $this->image,
        ]);
    }
}
