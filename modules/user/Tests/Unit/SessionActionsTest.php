<?php

namespace Modules\User\Tests\Unit;

use Modules\User\Patterns\Actions\{
    ScheduleSessionCommand,
    ModifySessionCommand,
    CancelSessionCommand
};
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Models\MentorshipSession;
use Modules\User\Models\User;
use Tests\TestCase;

class SessionActionsTest extends TestCase
{

    private SessionService $sessionService;
    private User $mentor;
    private User $mentee;

    public function setUp(): void
    {
        parent::setUp();
        $this->sessionService = new SessionService();

        $this->mentor = User::factory()->create();
        $this->mentee = User::factory()->create();
    }


    public function test_schedule_session_successfully()
    {
        $command = new ScheduleSessionCommand([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'duration' => 60,
            'notes' => 'Test'
        ], $this->sessionService);

        $result = $command->execute();

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('mentorship_sessions', [
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'status' => 'scheduled'
        ]);
    }


    public function test_cannot_schedule_less_than_one_hour()
    {
        $command = new ScheduleSessionCommand([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addMinutes(30)->format('Y-m-d H:i:s'),
            'duration' => 60
        ], $this->sessionService);

        $result = $command->execute();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Hour', $result['message']);
    }


    public function test_cannot_schedule_with_nonexistent_mentee()
    {
        $command = new ScheduleSessionCommand([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => 9999,
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'duration' => 60
        ], $this->sessionService);

        $result = $command->execute();

        $this->assertFalse($result['success']);
    }


    public function test_undo_schedule_command()
    {
        $command = new ScheduleSessionCommand([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'duration' => 60
        ], $this->sessionService);

        $result = $command->execute();
        $sessionId = $result['session_id'];

        $this->assertDatabaseHas('mentorship_sessions', ['id' => $sessionId]);

        $undoResult = $command->undo();
        $this->assertTrue($undoResult['success']);

        $this->assertDatabaseMissing('mentorship_sessions', ['id' => $sessionId]);
    }


    public function test_modify_session_successfully()
    {
        $session = MentorshipSession::create([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'status' => 'scheduled'
        ]);

        // عدّل الجلسة
        $command = new ModifySessionCommand(
            $session->id,
            ['duration' => 90],
            $this->sessionService
        );

        $result = $command->execute();

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'duration' => 90
        ]);
    }


    public function test_undo_modify_session()
    {
        $session = MentorshipSession::create([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'status' => 'scheduled'
        ]);

        $command = new ModifySessionCommand(
            $session->id,
            ['duration' => 90],
            $this->sessionService
        );

        $command->execute();

        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'duration' => 90
        ]);

        $undoResult = $command->undo();
        $this->assertTrue($undoResult['success']);

        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'duration' => 60
        ]);
    }


    public function test_cancel_session_successfully()
    {
        $session = MentorshipSession::create([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'status' => 'scheduled'
        ]);

        $command = new CancelSessionCommand($session->id, $this->sessionService);
        $result = $command->execute();

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'status' => 'cancelled'
        ]);
    }


    public function test_undo_cancel_session()
    {
        $session = MentorshipSession::create([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'status' => 'scheduled'
        ]);

        $command = new CancelSessionCommand($session->id, $this->sessionService);
        $command->execute();

        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'status' => 'cancelled'
        ]);

        $undoResult = $command->undo();
        $this->assertTrue($undoResult['success']);

        $this->assertDatabaseHas('mentorship_sessions', [
            'id' => $session->id,
            'status' => 'scheduled'
        ]);
    }


    public function test_cannot_cancel_already_cancelled_session()
    {
        $session = MentorshipSession::create([
            'mentor_id' => $this->mentor->id,
            'mentee_id' => $this->mentee->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'status' => 'cancelled'
        ]);

        $command = new CancelSessionCommand($session->id, $this->sessionService);
        $result = $command->execute();

        $this->assertFalse($result['success']);
    }
}

