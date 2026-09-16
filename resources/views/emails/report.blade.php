<x-mail::message>
# Report: {{ $report->name }}

Hello {{ $report->user->name }},

Please find attached your periodic report.

Report Details:
- Report Name: {{ $report->name }}
- Frequency: {{ $report->frequency === \App\Enums\Frequency::DAILY ? 'Daily' : 'Weekly' }}
- Keywords: {{ implode(', ', $report->keywords) }}

Thank you.
</x-mail::message>
