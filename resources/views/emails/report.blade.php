<x-mail::message>
# Report: {{ $report->name }}

Hello {{ $report->user->name }},

Please find attached your periodic report.

Report Details:
- Report Name: {{ $report->name }}
- Frequency: {{ $report->frequency->value === 1 ? 'Daily' : 'Weekly' }}
- Keywords: {{ implode(', ', $report->keywords) }}

Thank you.
</x-mail::message>
