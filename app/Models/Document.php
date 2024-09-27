<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar de manera masiva.
     *
     * @var array
     */
	protected $fillable = [
		'title',
		'description',
		'relevancia',
		'fecha_aprobacion',
		'path',
		'user_id',
		'aprobado',
		'aprobado_por',
		'revisado_por'
	];

    /**
     * Relación entre el documento y el usuario.
     * Un documento pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

	// Relación para el usuario que aprobó el documento
	public function aprobadoPor()
	{
		return $this->belongsTo(User::class, 'aprobado_por');
	}

	// Relación para el usuario que rechazó el documento
	public function revisadoPor()
	{
		return $this->belongsTo(User::class, 'revisado_por');
	}
}
