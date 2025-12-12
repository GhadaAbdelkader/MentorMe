<?php

namespace Modules\User\Patterns\Services;

use Exception;
use Carbon\Carbon;
use Modules\User\Models\MentorshipSession;
use Modules\User\Models\User;
use Modules\User\Patterns\Notifications\SessionCancelledNotification;
use Modules\User\Patterns\Validation\SessionValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SessionService
{
    public function createSession(array $data): array
    {
        SessionValidation::validateMentee((int)$data['mentee_id']);
        SessionValidation::validateScheduleTime($data['scheduled_at']);
        SessionValidation::validateDuration((int)$data['duration']);

        $session = MentorshipSession::create(array_merge($data, [
            'status' => $data['status'] ?? 'scheduled'
        ]));

        return [
            'success' => true,
            'session_id' => $session->id,
            'after' => $session->toArray(),
            'message' => 'The session was created successfully',
        ];
    }

    public function updateSession(int $sessionId, array $data, bool $saveQuietly = false): array
    {
        $session = MentorshipSession::find($sessionId);

        if (!$session) {
            return ['success' => false, 'message' => 'The session does not exist'];
        }

        // keep a copy before making changes
        $before = $session->toArray();

        // validate if relevant fields exist
        if (isset($data['scheduled_at'])) {
            SessionValidation::validateScheduleTime($data['scheduled_at']);
        }
        if (isset($data['duration'])) {
            SessionValidation::validateDuration((int)$data['duration']);
        }

        // apply update
        $session->fill($data);

        if ($saveQuietly) {
            $session->saveQuietly();
        } else {
            $session->save();
        }

        $after = $session->toArray();

        return [
            'success' => true,
            'message' => 'The session was updated successfully',
            'before' => $before,
            'after' => $after
        ];
    }

    public function cancelSession($session): array
    {
        $before = $session->toArray();

        // حدث تغيير الحالة في الـ DB
        $session->status = 'cancelled';
        $session->save();

        // إرسال Notification مع try/catch
        $mentee = User::find($session->mentee_id);
        if ($mentee) {
            try {
                $mentee->notify((new SessionCancelledNotification($session->toArray()))
                    ->delay(now()->addSeconds(5)));
            } catch (\Exception $e) {
                Log::error('Notification error: ' . $e->getMessage());
            }
        }

        $session->refresh();

        return [
            'success' => true,
            'message' => 'The session has been cancelled',
            'before' => $before,
            'after' => $session->toArray()
        ];
    }
    public function deleteSession(int $sessionId): array
    {
        $session = MentorshipSession::find($sessionId);

        if (!$session) {
            return ['success' => false, 'message' => 'The session does not exist'];
        }

        $session->delete();

        return [
            'success' => true,
            'message' => 'The session has been deleted',
        ];
    }

    /**
     * Find a session with relations (mentor, mentee)
     */
    public function findWithRelations(int $sessionId): ?MentorshipSession
    {
        return MentorshipSession::with(['mentee', 'mentor'])->find($sessionId);
    }

    /**
     * Force delete  a session
     */
    public function forceDeleteSession(int $sessionId): array
    {
        $session = $this->findWithRelations($sessionId);
        if (!$session) {
            return ['success' => false, 'message' => 'The session does not exist'];
        }

        $session->forceDelete();
        return ['success' => true, 'message' => 'The session was permanently deleted'];
    }

    /**
     * Restore/create session from data (used for undo delete)
     */
    public function restoreSessionFromData(array $sessionData): array
    {
        try {
            // Avoid forcing id unless safe; create new record to avoid id collision
            $session = new MentorshipSession();

            $fillable = [
                'mentor_id',
                'mentee_id',
                'scheduled_at',
                'duration',
                'status',
                'notes',
            ];

            foreach ($fillable as $field) {
                if (isset($sessionData[$field])) {
                    $session->$field = $sessionData[$field];
                }
            }

            // created_at/updated_at will be set automatically or can be set via saveQuietly
            $session->saveQuietly();

            return ['success' => true, 'message' => 'Session restored', 'session_id' => $session->id];
        } catch (Exception $e) {
            \Log::error('restoreSessionFromData failed: ' . $e->getMessage(), ['data' => $sessionData]);
            return ['success' => false, 'message' => 'Session restore failed: ' . $e->getMessage()];
        }
    }

    /**
     * Validate schedule helper (kept for completeness)
     */
    private function validateSchedule(array $data): void
    {
        if (empty($data['mentor_id']) || empty($data['mentee_id'])) {
            throw new Exception("Mentor and Mentee are required");
        }

        if ($data['mentor_id'] == $data['mentee_id']) {
            throw new Exception("The mentor cannot be the mentor");
        }

        if ($data['duration'] <= 0) {
            throw new Exception("The duration must be greater than zero.");
        }

        if (Carbon::parse($data['scheduled_at'])->lessThan(now()->addHour())) {
            throw new Exception("The session should be at least one hour from now.");
        }
    }
}
