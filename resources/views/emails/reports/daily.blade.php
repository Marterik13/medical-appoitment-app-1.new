<x-mail::message>
# Reporte Diario de Citas - {{ date('d/m/Y') }}

Hola, se adjunta la lista de citas programadas para el día de hoy.

<x-mail::table>
| Hora | Paciente | DNI | Doctor | Motivo |
| :--- | :--- | :--- | :--- | :--- |
@foreach($appointments as $app)
| {{ \Carbon\Carbon::parse($app->start_time)->format('H:i') }} | {{ $app->patient->user->name }} | {{ $app->patient->user->id_number }} | {{ $app->doctor->name }} | {{ $app->reason ?: 'N/A' }} |
@endforeach
</x-mail::table>

Total de citas: **{{ $appointments->count() }}**

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
