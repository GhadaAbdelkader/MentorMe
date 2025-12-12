<?php

namespace Modules\User\Patterns\Validation;

use Modules\User\Models\MentorshipSession;
use Modules\User\Models\User;
use Carbon\Carbon;

class SessionValidation
{


    public static function validateScheduleTime(string $scheduledAt): void
    {
        $scheduled = Carbon::parse($scheduledAt);
        $minimumTime = Carbon::now()->addHour();

        if ($scheduled->lessThanOrEqualTo($minimumTime)) {
            throw new \InvalidArgumentException(
                'The session must be after an hour from now'
            );
        }
    }


    public static function validateMentee(int $menteeId): void
    {
        if (!User::find($menteeId)) {
            throw new \InvalidArgumentException(
                'The mentee you required is not available'
            );
        }
    }


    public static function validateDuration(int $duration): void
    {
        if ($duration < 15 || $duration > 480) {
            throw new \InvalidArgumentException(
                'The session Duration must be between 15 to 45 minutes'
            );
        }
    }


    public static function authorizeSessionOwner(int $userId, string $role, int $sessionId): void
    {
        $session = MentorshipSession::find($sessionId);

        if (!$session) {
            throw new \Exception('The session is not found');
        }

        if ($role === 'mentor' && $session->mentor_id !== $userId) {
            throw new \Exception('You are not authorized to modify this session');
        }

        if ($role === 'mentee' && $session->mentee_id !== $userId) {
            throw new \Exception('You are not authorized to view this session');
        }
    }



    public static function validateUndoTimeframe(Carbon $executedAt): void
    {
        $hoursElapsed = $executedAt->diffInHours(Carbon::now());

        if ($hoursElapsed > 24) {
            throw new \Exception(
                'You can not undo changes after 24 hour of the session creation'
            );
        }
    }


    public static function validateCancellable(MentorshipSession $session): void
    {
        if ($session->status === 'cancelled') {
            throw new \Exception('The session is already canceled');
        }

        if ($session->status === 'completed') {
            throw new \Exception('You can not cancel session already completed');
        }
    }
}
