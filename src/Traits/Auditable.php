<?php

/**
 * Davidiwezulu/AuditTrail
 *
 * @license MIT © 2021 David Iwezulu
 */

namespace Davidiwezulu\AuditTrail\Traits;

use Illuminate\Support\Facades\Auth;
use Davidiwezulu\AuditTrail\Models\AuditTrail;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    /**
     * Boot the Auditable trait for a model.
     *
     * @return void
     */
    public static function bootAuditable(): void
    {
        foreach (static::getAuditEvents() as $event) {
            static::$event(static function ($model) use ($event) {
                $model->audit($event);
            });
        }
    }

    /**
     * Record an audit trail for the model.
     *
     * @param  string  $event
     * @return void
     */
    public function audit(string $event): void
    {
        AuditTrail::create([
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'user_id'        => Auth::id(),
            'old_values'     => $this->getOriginal(),
            'new_values'     => $this->getDirty(),
            'event'          => $event,
        ]);
    }

    /**
     * Get the list of events that should trigger an audit.
     *
     * @return array
     */
    protected static function getAuditEvents(): array
    {
        return ['created', 'updated', 'deleted'];
    }

    /**
     * Get all of the model's audit trails.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function auditTrails(): MorphMany
    {
        return $this->morphMany(AuditTrail::class, 'auditable');
    }

    /**
     * Rollback the model to a specific audit trail entry.
     *
     * @param  int  $auditTrailId
     * @return bool
     */
    public function rollbackTo(int $auditTrailId): bool
    {
        $audit = $this->auditTrails()->findOrFail($auditTrailId);
        $this->fill($audit->old_values);
        return $this->save();
    }
}
