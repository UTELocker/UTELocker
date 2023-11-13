<?php

namespace App\Http\Controllers\Api\HelpCalls;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Services\Admin\HelpCalls\HelpCallService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\HelpCall\StoreHelpCallRequest;
use App\Services\Admin\HelpCalls\HelpCallCommentService;
use App\Http\Requests\Api\HelpCall\StoreHelpCallCommentRequest;
use App\Http\Requests\Api\HelpCall\UpdateHelpCallRequest;

class HelpCallController extends Controller
{
    public ?HelpCallService $helpCallService;
    public ?HelpCallCommentService $helpCallCommentService;

    public function __construct(HelpCallService $helpCallService, HelpCallCommentService $helpCallCommentService)
    {
        parent::__construct();
        $this->helpCallService = $helpCallService;
        $this->helpCallCommentService = $helpCallCommentService;
    }

    public function getHelpCallUser ()
    {
        $helpCalls = $this->helpCallService->getHelpCallsUser();
        return Reply::successWithData('Get help calls successfully',
            [
                'data' => $helpCalls
            ]
        );
    }

    public function getHelpCallAdmin ()
    {
        $helpCalls = $this->helpCallService->getHelpCallAdmin();
        return Reply::successWithData('Get help calls successfully',
            [
                'data' => $helpCalls
            ]
        );
    }

    public function store(StoreHelpCallRequest $request)
    {
        $helpCall = $this->helpCallService->add($request->all());
        return Reply::successWithData('Create help call successfully',
            [
                'data' => $helpCall
            ]
        );
    }

    public function show($id)
    {
        $helpCall = $this->helpCallService->getWithComment($id);
        return Reply::successWithData('Get help call successfully',
            [
                'data' => $helpCall
            ]
        );
    }

    public function comment($id, StoreHelpCallCommentRequest $request)
    {
        $inputs = $request->all();
        $inputs['help_call_id'] = $id;
        $helpCall = $this->helpCallCommentService->add($inputs);
        return Reply::successWithData('Comment help call successfully',
            [
                'data' => $helpCall
            ]
        );
    }

    public function update($id, UpdateHelpCallRequest $request)
    {
        $helpCall = $this->helpCallService->get($id);
        $helpCall = $this->helpCallService->update($helpCall, $request->all());
        return Reply::successWithData('Update help call successfully',
            [
                'data' => $helpCall
            ]
        );
    }
}
