<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\ReportService;
use App\Enums\Frequency;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\Api\V1\Report\StoreReportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function store(StoreReportRequest $request): JsonResponse
    {
        $report = $this->reportService->create(
            $request->user()->id,
            $request->validated('name'),
            Frequency::from($request->validated('frequency')),
            $request->validated('keywords'),
        );

        return ResponseJson::success($report, __('messages.report_created'), 201);
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $reports = $this->reportService->listByUserIdPaginated($request->user()->id, $perPage);

        return ResponseJson::success($reports, __('messages.success'));
    }
}
