<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .report-title { font-size: 18px; margin-top: 5px; color: #666; }
        .info-section { margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #e5e7eb; }
        td { padding: 10px; border: 1px solid #e5e7eb; font-size: 12px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">HEALTHIFY</div>
        <div class="report-title">Reporte Diario de Citas - {{ $date }}</div>
        <div style="font-size: 12px; color: #888;">Destinatario: {{ $recipientType }}</div>
    </div>

    <div class="info-section">
        <p>A continuación se detalla la lista de citas programadas para el día de hoy.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Paciente</th>
                @if($recipientType === 'Administrador')
                    <th>Doctor</th>
                @endif
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $app)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($app->start_time)->format('H:i') }}</td>
                    <td>{{ $app->patient->user->name }}</td>
                    @if($recipientType === 'Administrador')
                        <td>{{ $app->doctor->name }}</td>
                    @endif
                    <td>{{ $app->reason }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $recipientType === 'Administrador' ? 4 : 3 }}" style="text-align: center;">No hay citas programadas para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Este es un reporte generado automáticamente por el Sistema de Gestión Hospitalaria Healthify.<br>
        &copy; {{ date('Y') }} Healthify Hospital. Todos los derechos reservados.
    </div>
</body>
</html>
