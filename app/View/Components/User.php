<?php

namespace App\View\Components;

use App\Classes\Files;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class User extends Component
{
    public $user;
    public $avatar;
    /**
     * Create a new component instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        if ($user->avatar) {
            $this->avatar = Files::getImageUrl(
                $user->avatar, 'user-avatar',
                Files::USER_UPLOAD_FOLDER
            );
        } else {
            $this->avatar = asset('images/default/avatarDefault.png');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user', [
            'user' => $this->user,
            'avatar' => $this->avatar,
        ]);
    }
}
