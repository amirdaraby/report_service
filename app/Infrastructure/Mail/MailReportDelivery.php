<?php

namespace App\Infrastructure\Mail;

use App\Application\Contracts\Delivery\ReportDelivery;
use App\Mail\ReportMail;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;

final class MailReportDelivery implements ReportDelivery
{
    public function send(Report $report, string $filePath): void
    {
        Mail::to($report->user->email)->send(
            new ReportMail($report, $filePath)
        );
    }
}
