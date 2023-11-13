<?php

namespace App\Http\Controllers\Api\HelpCalls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Reply;
use App\Services\Admin\HelpCalls\HelpCallStdProblemsService;
use App\Http\Requests\Api\HelpCallStd\StoreHelpCallStdProblemRequest;

class HelpCallStdProblemController extends Controller
{
    private HelpCallStdProblemsService $helpCallStdProblemService;

    public function __construct(HelpCallStdProblemsService $helpCallStdProblemService)
    {
        parent::__construct();
        $this->helpCallStdProblemService = $helpCallStdProblemService;
    }

    public function index()
    {
        $helpCallStdProblem = $this->helpCallStdProblemService->getAllOfClient();
        return Reply::successWithData('Get help call std problem successfully',
            [
                'data' => $helpCallStdProblem
            ]
        );
    }


    public function store(StoreHelpCallStdProblemRequest $request)
    {
        $inputs = $request->all();
        $helpCallStdProblem = $this->helpCallStdProblemService->add($inputs);
        return Reply::successWithData('Add help call std problem successfully',
            [
                'data' => $helpCallStdProblem
            ]
        );
    }

    public function update(StoreHelpCallStdProblemRequest $request, $id)
    {
        $inputs = $request->all();
        $helpCallStdProblem = $this->helpCallStdProblemService->update($id, $inputs);
        return Reply::successWithData('Update help call std problem successfully',
            [
                'data' => $helpCallStdProblem
            ]
        );
    }

    public function destroy($id)
    {
        $helpCallStdProblem = $this->helpCallStdProblemService->delete($id);
        return Reply::successWithData('Delete help call std problem successfully',
            [
                'data' => $helpCallStdProblem
            ]
        );
    }
}
