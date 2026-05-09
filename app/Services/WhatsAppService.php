<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a confirmation message via WhatsApp.
     */
    public function sendConfirmation(Appointment $appointment)
    {
        $phone = $appointment->patient->user->phone;
        $doctorName = $appointment->doctor->name;
        $date = $appointment->date->format('d/m/Y');
        $time = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
        $pdfUrl = config('app.url') . "/admin/appointments/{$appointment->id}/pdf";

        $message = "Hola {$appointment->patient->user->name}, tu cita con el Dr(a). {$doctorName} ha sido confirmada para el día {$date} a las {$time}. Puedes descargar tu comprobante PDF aquí: {$pdfUrl}";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send a reminder message via WhatsApp.
     */
    public function sendReminder(Appointment $appointment)
    {
        $phone = $appointment->patient->user->phone;
        $doctorName = $appointment->doctor->name;
        $time = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');

        $message = "Recordatorio: Mañana tienes una cita con el Dr(a). {$doctorName} a las {$time}. Por favor, confirma tu asistencia.";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Helper to send the HTTP request (CallMeBot API - FREE).
     */
    protected function sendMessage($phone, $message)
    {
        $apiKey = env('CALLMEBOT_API_KEY');
        
        if (!$apiKey) {
            Log::warning("CallMeBot API Key missing. Message logged: {$message}");
            return false;
        }

        try {
            // Limpieza absoluta según tu tip clave: solo números, sin espacios ni el signo +
            $numbersOnly = preg_replace('/[^0-9]/', '', $phone);
            
            // Aseguramos el formato 521 para México si tiene 10 dígitos
            if (strlen($numbersOnly) == 10) {
                $miNumero = "521" . $numbersOnly;
            } elseif (str_starts_with($numbersOnly, '52') && strlen($numbersOnly) == 12) {
                $miNumero = "521" . substr($numbersOnly, 2);
            } else {
                $miNumero = $numbersOnly;
            }

            $mensajeCodificado = urlencode($message);
            $url = "https://api.callmebot.com/whatsapp.php?phone={$miNumero}&text={$mensajeCodificado}&apikey={$apiKey}";

            // Ejecutamos la URL mágica
            $response = Http::get($url);

            if ($response->successful()) {
                Log::info("CallMeBot WhatsApp enviado con éxito a {$miNumero}");
                return true;
            } else {
                Log::error("Error en CallMeBot: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Excepción en CallMeBot: " . $e->getMessage());
            return false;
        }
    }
}
