<?php

use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$appointment = Appointment::with(['patient.user', 'doctor'])->first();

if (!$appointment) {
    echo "No appointments found to test.\n";
    exit;
}

try {
    $pdf = Pdf::loadView('pdf.appointment-voucher', ['appointment' => $appointment]);
    $output = $pdf->output();
    file_put_contents(__DIR__.'/test-voucher.pdf', $output);
    echo "PDF generated successfully at scratch/test-voucher.pdf\n";
} catch (\Exception $e) {
    echo "Error generating PDF: " . $e->getMessage() . "\n";
}
