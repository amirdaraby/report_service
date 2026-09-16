<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Report $report,
        public string $filePath,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Report: {$this->report->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.report',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('local', $this->filePath)
                ->as("report_{$this->report->id}.xlsx")
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
