<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    use AuthorizesRequests;
    //lleva toda la logica del panel de control
    //Muestra el panel de control en 4 columnas
    //Pendientes, recibidas, finalizadas y canceladas

    public function index(Request $request){
        //obtenemos al usuario autenticado
        $user = $request->user();

        //limpiamos recetas antiguas de mas de 30 dias
        $this->cleanupOldFinalized($user);
        
        //recetas pendientes
        $pending = $user->prescriptions()
            ->where('status', 'sent_to_patient')
            ->latest()
            ->get();

        //recetas recibidas
        $received = $user->prescriptions()
            ->where('status', 'completed')
            ->latest()
            ->get();

        //recetas finalizadas
        $finalized = $user->prescriptions()
            ->where('status', 'finalized')
            ->whereNotNull('issued_at')
            ->where('issued_at', '>=', now()->subDays(30))
            ->orderByDesc('issued_at')
            ->limit(50)
            ->get();

        //recetas canceladas
        $cancelled = $user->prescriptions()
            ->where('status', 'cancelled')
            ->latest()
            ->limit(50)
            ->get();

        
        return view('dashboard', compact('pending', 'received', 'finalized', 'cancelled'));
    }

    /**
     * Muestra el formulario para crear una nueva receta.
     * Acá el médico completa solo RP e (idealmente) notas.
     */

    public function create(){
        return view('prescriptions.create');
    }

    /**
     * Guarda la nueva receta en la base de datos en estado "sent_to_patient".
     * Luego, el panel mostrará el link que se envía al paciente.
     */

    public function store(Request $request){
        //validamos lo que viene del formulario
        $data = $request->validate([
            'rp' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        //usuario actual
        $user = $request->user();

        //creamos la receta asociada al usuario
        $prescription = $user->prescriptions()->create([
            'rp' => $data['rp'],
            'notes' => $data['notes'] ?? null,
            'status' => 'sent_to_patient',
            //generamos un token publico para el link del paciente
            'public_token' => Str::uuid()->toString(),
        ]);

        //mensaje flash de exito
        return redirect()->route('dashboard')
            ->with('status', 'Receta creada exitosamente y lista para enviar al paciente.');
    }

    /**
     * Cancela una receta pendiente (solo si está en estado sent_to_patient).
     */

     public function cancel(Prescription $prescription){
        //aseguramos que la receta pertenezca al usuario autenticado
        $this->authorizeAccess($prescription);

        //solo se puede cancelar si está en estado sent_to_patient
        if ($prescription->status !== 'sent_to_patient'){
            abort(403, 'Solo se pueden Cancelar recetas Pendientes.');
        }
        $prescription->status = 'cancelled';
        $prescription->save();

        return redirect()->route('dashboard')->with('status', 'Receta cancelada exitosamente.');
     }
    
    //Genera la receta final a partir de una receta recibida (completed).

    public function generate(Prescription $prescription){
        $this->authorizeAccess($prescription);

        //solo se puede generar si está en estado completed
        if ($prescription->status !== 'completed'){
            abort(403, 'Solo se pueden Generar recetas que hayan sido completadas por el paciente.');
        }

        //seteamos fecha de emision si no existe
        if (!$prescription->issued_at){
            $prescription->issued_at = now();
        }
        $prescription->status = 'finalized';

        //url publica de verificacion
        $verificationUrl = route('prescriptions.verify', $prescription->public_token);

        //generamos el PDF usando la vista
        $pdf = Pdf::loadView('prescriptions.pdf', [
            'prescription' => $prescription,
            'doctor' => $prescription->user,
            'verificationUrl' => $verificationUrl,
        ]);

        //nombre y ruta del archivo dentro de storage/app/public
        $fileName = 'prescription_' . $prescription->id . '.pdf';
        $filePath = 'prescriptions/' . $fileName;

        //guardamos el PDF en public
        \Storage::disk('public')->put($filePath, $pdf->output());

        //guardamos la ruta del PDF en la receta
        $prescription->pdf_path = $filePath;
        $prescription->save();

        return redirect()->route('dashboard')->with('status', 'Receta generada exitosamente.');
    }

    //Método privado para asegurar que la receta pertenece al médico logueado.
    private function authorizeAccess(Prescription $prescription): void{
        $user = Auth::user();
        if ($prescription->user_id !== $user->id){
            abort(403, 'No tenés permiso para acceder a esta receta.');
        }
    }

    //Limpia recetas finalizadas de mas de 30 dias
    private function cleanupOldFinalized($user): void{
        //buscamos las recetas de 30 días o más
        $oldFinalized = $user->prescriptions()
            ->where('status', 'finalized')
            ->whereNotNull('issued_at')
            ->where('issued_at', '<=', now()->subDays(30))
            ->orderBy('issued_at', 'asc')
            ->get();

        $count = $oldFinalized->count();

        if ($count <= 1){
            return; //no hay nada que limpiar
        }
        
        //eliminamos la mitad de las recetas viejas
        $toDelete = intdiv($count, 2); 

        $oldFinalized->take($toDelete)->each(function ($prescription){
            $prescription->delete();
        });
    } 

    public function viewPdf(Prescription $prescription){
        $this->authorizeAccess($prescription);

        if (! $prescription->pdf_path){
            abort(404, 'La receta aún no ha sido generada en formato PDF.');
        }

        $path = storage_path('app/public/' . $prescription->pdf_path);

        if (!file_exists($path)){
            abort(404, 'El archivo PDF de la receta no se encontró.');
        }

        //retornamos el archivo PDF para ver en el navegador
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }

    public function downloadPdf(Prescription $prescription){

        $this->authorizeAccess($prescription);

        if (! $prescription->pdf_path) {
            abort(404, 'Esta receta aún no tiene un PDF generado.');
        }

        $path = storage_path('app/public/'.$prescription->pdf_path);

        if (! file_exists($path)) {
            abort(404, 'Archivo de receta no encontrado.');
        }

        $fileName = 'receta_medica_'.$prescription->id.'.pdf';

        return response()->download($path, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function destroy(Prescription $prescription){
        //nos aseguramos que la receta pertenezca al usuario autenticado
        $this->authorizeAccess($prescription);

        //solo se pueden eliminar recetas finalizadas
        if ($prescription->status !== 'finalized'){
            abort(403, 'Solo se pueden eliminar recetas Finalizadas.');
        }

        //elimina la receta
        $prescription->delete();

        //mensaje flash de exito
        return redirect()->route('dashboard')->with('status', 'Receta eliminada exitosamente.');
    }
}
