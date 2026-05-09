<x-mail::message>
# Confirmación de Cita Médica - Healthify

Estimado(a) **{{ $appointment->patient->user->name }}**,

Le informamos que su cita médica ha sido agendada con éxito en nuestro sistema. Este mensaje también ha sido notificado al **Dr(a). {{ $appointment->doctor->name }}**.

<x-mail::panel>
**Detalles de su compromiso:**
- 📅 **Fecha:** {{ $appointment->date->format('d/m/Y') }}
- ⏰ **Hora:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
- 🩺 **Especialidad:** {{ $appointment->doctor->specialty }}
- 👨‍⚕️ **Doctor:** {{ $appointment->doctor->name }}
</x-mail::panel>

**Motivo de consulta:**
_{{ $appointment->reason }}_

Adjunto a este correo encontrará el **Comprobante de Cita en formato PDF**, el cual deberá presentar (ya sea impreso o digital) al momento de su llegada al hospital.

<x-mail::button :url="config('app.url') . '/admin/appointments'">
Gestionar mi Cita
</x-mail::button>

*Nota: Por favor, llegue 15 minutos antes de la hora programada.*

Atentamente,<br>
**Servicios Médicos Healthify**
</x-mail::message>
