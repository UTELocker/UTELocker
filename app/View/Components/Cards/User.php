<?php

namespace App\View\Components\Cards;

use App\Classes\Files;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class User extends Component
{
    public $image;
    /**
     * Create a new component instance.
     */
    public function __construct($image)
    {
        $this->image = $image;
        if ($this->image) {
            $this->image = Files::getImageUrl(
                $this->image, 'user-avatar',
                Files::USER_UPLOAD_FOLDER
            );
        } else {
            $this->image = asset('images/default/avatarDefault.png');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.user');
    }
}
