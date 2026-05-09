<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita Médica</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            color: #4f46e5;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .data-row {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .badge {
            background-color: #4f46e5;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Healthify Appointments</h1>
        <p>Sistema de Gestión Médica</p>
        <p>Comprobante de Cita #{{ $appointment->id }}</p>
    </div>

    <div class="section">
        <div class="section-title">Información del Paciente</div>
        <div class="data-row">
            <span class="label">Nombre:</span>
            <span>{{ $appointment->patient->user->name }}</span>
        </div>
        <div class="data-row">
            <span class="label">DNI:</span>
            <span>{{ $appointment->patient->user->id_number }}</span>
        </div>
        <div class="data-row">
            <span class="label">Teléfono:</span>
            <span>{{ $appointment->patient->user->phone }}</span>
        </div>
        <div class="data-row">
            <span class="label">Email:</span>
            <span>{{ $appointment->patient->user->email }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Detalles de la Cita</div>
        <div class="data-row">
            <span class="label">Doctor(a):</span>
            <span>{{ $appointment->doctor->name }}</span>
        </div>
        <div class="data-row">
            <span class="label">Email Doctor:</span>
            <span>{{ $appointment->doctor->email }}</span>
        </div>
        <div class="data-row">
            <span class="label">Especialidad:</span>
            <span>{{ $appointment->doctor->specialty }}</span>
        </div>
        <div class="data-row">
            <span class="label">Fecha:</span>
            <span>{{ $appointment->date->format('d/m/Y') }}</span>
        </div>
        <div class="data-row">
            <span class="label">Hora:</span>
            <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</span>
        </div>
        <div class="data-row">
            <span class="label">Estado:</span>
            <span class="badge">Programada</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Motivo de Consulta</div>
        <p>{{ $appointment->reason ?: 'No especificado' }}</p>
    </div>

    <div class="footer">
        <p>Por favor, llegue 15 minutos antes de su cita. Si necesita cancelar, hágalo con al menos 24 horas de anticipación.</p>
        <p>&copy; {{ date('Y') }} Healthify Hospital. Todos los derechos reservados.</p>
    </div>
</body>
</html>
