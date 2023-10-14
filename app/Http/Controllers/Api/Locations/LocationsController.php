<?php

namespace App\Http\Controllers\Api\Locations;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Admin\Locations\LocationService;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public ?LocationService $locationService;

    public function __construct(
        LocationService $locationService
    ) {
        parent::__construct();
        $this->locationService = $locationService;
    }

    public function get() {
        $locations = $this->locationService->getWithLocker()->toArray();
        return Reply::successWithData('Get List Location Successfully', [
            'data' => $locations
        ]);
    }
}
