<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\MeetingController;

class TestUpdateMeeting extends Command
{
    protected $signature = 'test:update-meeting';
    protected $description = 'Test updating a meeting';

    public function handle()
    {
        $meeting = Meeting::first();
        $user = User::first();
        
        auth()->login($user);

        $request = Request::create(route('meetings.update', $meeting), 'PUT', [
            'title' => 'Test title',
            'description' => 'Test desc',
            'meeting_type_id' => $meeting->meeting_type_id,
            'department_id' => $meeting->department_id,
            'start_time' => $meeting->start_time->toDateTimeString(),
            'end_time' => $meeting->end_time->toDateTimeString(),
            'location' => 'Room 1',
            'participants' => [1, 2, 3],
        ]);

        $controller = app(MeetingController::class);
        $response = $controller->update($request, $meeting);

        $this->info('Done. Status: ' . $response->getStatusCode());
    }
}
