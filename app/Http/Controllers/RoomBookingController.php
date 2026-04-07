<?php

namespace App\Http\Controllers;

use App\Models\RoomBooking;
use App\Models\Meeting;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = RoomBooking::with('user')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
            
        return view('room-bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::active()->orderBy('name')->get();
        return view('room-bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pic_name' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $room = Room::findOrFail($request->room_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        // Anti-clash check
        $clashQuery = function($q) use ($startTime, $endTime) {
            $q->where('start_time', '<', $endTime)
              ->where('end_time', '>', $startTime);
        };

        $clashingRoomBooking = RoomBooking::where('room_id', $room->id)
            ->where('status', '!=', 'cancelled')
            ->where($clashQuery)
            ->exists();

        $clashingMeeting = Meeting::where('room_id', $room->id)
            ->where('is_online', false)
            ->where('status', '!=', 'cancelled')
            ->where($clashQuery)
            ->exists();

        if ($clashingRoomBooking || $clashingMeeting) {
            return back()->with('error', 'Ruangan tersebut sudah dibooking pada jam tersebut. Silakan pilih waktu berbeda.')
                         ->withInput();
        }
        
        $picName = $request->pic_name ?: auth()->user()->name;

        RoomBooking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'pic_name' => $picName,
            'location' => $room->name,
            'purpose' => $request->purpose,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'booked',
        ]);

        return redirect()->route('room-bookings.index')->with('success', 'Reservasi ruangan berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomBooking $roomBooking)
    {
        // Hanya yang membooking atau admin yang bisa mengedit
        if (auth()->id() !== $roomBooking->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit reservasi ini.');
        }

        $rooms = Room::active()->orderBy('name')->get();
        return view('room-bookings.edit', compact('roomBooking', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomBooking $roomBooking)
    {
        // Hanya yang membooking atau admin yang bisa mengedit
        if (auth()->id() !== $roomBooking->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit reservasi ini.');
        }

        $request->validate([
            'pic_name' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'purpose' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $room = Room::findOrFail($request->room_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        // Anti-clash check (exclude current booking)
        $clashQuery = function($q) use ($startTime, $endTime) {
            $q->where('start_time', '<', $endTime)
              ->where('end_time', '>', $startTime);
        };

        $clashingRoomBooking = RoomBooking::where('room_id', $room->id)
            ->where('id', '!=', $roomBooking->id)
            ->where('status', '!=', 'cancelled')
            ->where($clashQuery)
            ->exists();

        $clashingMeeting = Meeting::where('room_id', $room->id)
            ->where('is_online', false)
            ->where('status', '!=', 'cancelled')
            ->where($clashQuery)
            ->exists();

        if ($clashingRoomBooking || $clashingMeeting) {
            return back()->with('error', 'Ruangan tersebut sudah dibooking pada jam tersebut. Silakan pilih waktu berbeda.')
                         ->withInput();
        }

        $roomBooking->update([
            'room_id' => $room->id,
            'pic_name' => $request->pic_name ?: $roomBooking->user->name,
            'location' => $room->name,
            'purpose' => $request->purpose,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('room-bookings.index')->with('success', 'Reservasi ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomBooking $roomBooking)
    {
        // Hanya yang membooking atau admin yang bisa menghapus
        if (auth()->id() !== $roomBooking->user_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan reservasi ini.');
        }

        $roomBooking->delete();

        return back()->with('success', 'Reservasi ruangan berhasil dibatalkan/dihapus.');
    }
}
