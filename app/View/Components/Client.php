<?php

namespace App\View\Components;

use App\Classes\Files;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Client extends Component
{
    public $client;
    public $logo;
    /**
     * Create a new component instance.
     */
    public function __construct($client)
    {
        $this->client = $client;
        if ($client->logo) {
            $this->logo = Files::getImageUrl(
                $client->logo, 'client-logo',
                Files::CLIENT_UPLOAD_FOLDER
            );
        } else {
            $this->logo = asset('images/default/logoDefault.png');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client', [
            'client' => $this->client,
            'logo' => $this->logo,
        ]);
    }
}
