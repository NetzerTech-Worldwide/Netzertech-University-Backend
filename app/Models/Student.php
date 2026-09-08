<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'university_id',
        'user_id',
        'matric_number',
        'jamb_reg_no',
        'faculty_id',
        'department_id',
        'programme_id',
        'level',
        'academic_session',
        'current_semester',
        'entry_mode',
        'cgpa',
        'standing',
        'digital_id_token',
    ];

    protected function casts(): array
    {
        return [
            'cgpa' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function hostelAllocations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HostelAllocation::class);
    }

    public function clinicRegistration(): HasOne
    {
        return $this->hasOne(ClinicRegistration::class);
    }

    public function clinicAppointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClinicAppointment::class);
    }

    public function digitalIdCard(): HasOne
    {
        return $this->hasOne(DigitalIdCard::class);
    }

    public function approvalRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    public function libraryBorrowRecords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LibraryBorrowRecord::class);
    }

    public function pgProfile(): HasOne
    {
        return $this->hasOne(PgStudentProfile::class);
    }

    public function pgProposals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PgProposal::class);
    }

    public function pgMilestones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PgThesisMilestone::class)->orderBy('chapter_number');
    }

    public function pgSupervisionLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PgSupervisionLog::class)->latest('meeting_date');
    }

    public function skills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentSkill::class);
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentProject::class);
    }

    public function certifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentCertification::class);
    }
}


