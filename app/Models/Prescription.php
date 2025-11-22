<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Prescription extends Model
{
    use HasFactory;

    //nombre de la tabla
    protected $table = 'prescriptions';

    //campos asignables
    protected $fillable = [
        'user_id',
        'patient_first_name',
        'patient_last_name',
        'patient_birth_date',
        'patient_age',
        'patient_dni',
        'patient_health_insurance',
        'rp',
        'notes',
        'status',
        'public_token',
        'issued_at',
        'pdf_path',
    ];

    //tipos de casting para columnas especificas
    protected $casts = [
        'patient_birth_date' => 'date',
        'issued_at' => 'datetime',
    ];

    //relacion: una receta pertenece a un usuario (medico)
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    //helpers para chequear el estado de la receta
    public function isPending(): bool{
        return $this->status === 'sent_to_patient';
    }

    public function isCompleted(): bool{
        return $this->status === 'completed';
    }

    public function isFinalized(): bool{
        return $this->status === 'finalized';
    }

    public function isCancelled(): bool{
        return $this->status === 'cancelled';
    }


}
