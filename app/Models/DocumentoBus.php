<?php

namespace App\Models;


use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\RegistroUsuarioController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class DocumentoBus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documentos_buses';

    protected $fillable = [
        'bus_id',
        'tipo_documento',
        'numero_documento',
        'fecha_emision',
        'fecha_vencimiento',
        'archivo_url',
        'estado',
        'observaciones',
        'dias_hasta_vencimiento',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    protected $appends = ['estado_badge', 'tipo_documento_nombre'];

    // ========================================
    // RELACIONES
    // ========================================

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function historial()
    {
        return $this->hasMany(HistorialDocumentoBus::class, 'documento_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'documento_id');
    }

    // ========================================
    // ACCESSORS (GETTERS)
    // ========================================

    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'vigente' => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Vigente</span>',
            'por_vencer' => '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Por Vencer</span>',
            'vencido' => '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Vencido</span>',
        ];

        return $badges[$this->estado] ?? '<span class="badge bg-secondary">Desconocido</span>';
    }

    /**
     * Los días restantes siempre se calculan desde hoy, no desde la fecha
     * en que el documento fue creado.
     */
    public function getDiasHastaVencimientoAttribute($value): int
    {
        return $this->calcularDiasRestantes();
    }

    /**
     * El estado visible se deriva de la fecha de vencimiento para evitar
     * que un valor persistido quede obsoleto con el paso de los días.
     */
    public function getEstadoAttribute($value): string
    {
        return $this->calcularEstado();
    }

    public function getTipoDocumentoNombreAttribute()
    {
        $tipos = [
            'permiso_operacion' => 'Permiso de Operación',
            'revision_tecnica' => 'Revisión Técnica',
            'seguro_vehicular' => 'Seguro Vehicular',
            'matricula' => 'Matrícula',
        ];

        return $tipos[$this->tipo_documento] ?? $this->tipo_documento;
    }

    // ========================================
    // MÉTODOS PERSONALIZADOS
    // ========================================

    /**
     * Calcula y actualiza el estado del documento según la fecha de vencimiento
     */
    public function actualizarEstado()
    {
        $this->aplicarCalculoActual();

        $this->save();

        return $this->estado;
    }

    public function calcularDiasRestantes(): int
    {
        if (! $this->fecha_vencimiento) {
            return 0;
        }

        return (int) Carbon::today()->diffInDays(
            Carbon::parse($this->fecha_vencimiento)->startOfDay(),
            false
        );
    }

    public function calcularEstado(): string
    {
        $diasRestantes = $this->calcularDiasRestantes();

        if ($diasRestantes < 0) {
            return 'vencido';
        }

        if ($diasRestantes <= 30) {
            return 'por_vencer';
        }

        return 'vigente';
    }

    public function aplicarCalculoActual(): void
    {
        $diasRestantes = $this->calcularDiasRestantes();

        $this->setAttribute('dias_hasta_vencimiento', $diasRestantes);
        $this->setAttribute('estado', $this->calcularEstado());
    }

    /**
     * Verifica si el documento está vigente
     */
    public function estaVigente()
    {
        return $this->estado === 'vigente';
    }

    /**
     * Verifica si el documento está vencido
     */
    public function estaVencido()
    {
        return $this->estado === 'vencido';
    }

    /**
     * Verifica si el documento está por vencer
     */
    public function estaPorVencer()
    {
        return $this->estado === 'por_vencer';
    }

    /**
     * Obtiene el color del estado
     */
    public function getColorEstado()
    {
        $colores = [
            'vigente' => 'success',
            'por_vencer' => 'warning',
            'vencido' => 'danger',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    /**
     * Obtiene el ícono del estado
     */
    public function getIconoEstado()
    {
        $iconos = [
            'vigente' => 'fa-check-circle',
            'por_vencer' => 'fa-exclamation-triangle',
            'vencido' => 'fa-times-circle',
        ];

        return $iconos[$this->estado] ?? 'fa-question-circle';
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeVigentes($query)
    {
        return $query->whereDate('fecha_vencimiento', '>', Carbon::today()->addDays(30));
    }

    public function scopePorVencer($query)
    {
        return $query->whereBetween('fecha_vencimiento', [
            Carbon::today()->toDateString(),
            Carbon::today()->addDays(30)->toDateString(),
        ]);
    }

    public function scopeVencidos($query)
    {
        return $query->whereDate('fecha_vencimiento', '<', Carbon::today());
    }

    public function scopeNoVigentes($query)
    {
        return $query->whereDate('fecha_vencimiento', '<=', Carbon::today()->addDays(30));
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    public function scopePorBus($query, $busId)
    {
        return $query->where('bus_id', $busId);
    }

    // ========================================
    // EVENTOS DEL MODELO
    // ========================================

    protected static function booted()
    {
        static::creating(function ($documento) {

            // Registrar usuario que creó el documento
            $documento->registrado_por = auth()->id();

            $documento->aplicarCalculoActual();
        });

        static::saving(function ($documento) {
            $documento->aplicarCalculoActual();
        });

        static::created(function ($documento) {
            HistorialDocumentoBus::create([
                'documento_id' => $documento->id,
                'accion' => 'creado',
                'descripcion' => 'Documento registrado en el sistema',
                'usuario_id' => auth()->id(),
            ]);
        });

        static::updated(function ($documento) {
            if ($documento->wasChanged('estado')) {
                HistorialDocumentoBus::create([
                    'documento_id' => $documento->id,
                    'accion' => 'actualizado_estado',
                    'descripcion' => 'Estado cambiado a: ' . $documento->estado,
                    'usuario_id' => auth()->id(),
                ]);
            }
        });
    }
}
