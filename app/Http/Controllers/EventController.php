<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $data = $this->validate($request, [
            // 'invitation_id' => ['required'],
            'title' => ['required'],
            'address' => ['required'],
            'landmark' => ['required'],
        ]);

        // $item = Invitation::where([["id", $data['invitation_id']], ["user_id", Auth::user()->id]])->first();
        $item = Invitation::where([["user_id", Auth::user()->id]])->first();
        
        if(empty($item)) abort(400, 'data not found');

        $data['invitation_id'] = $item['id'];
        try {
            $invite = Event::create($data);
            return response()->json($invite);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'failed ' ,
                'detail' => $e->errorInfo
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HttpRequest $request, $id)
    {
        $data = $this->validate($request, [
            'title' => ['required'],
            'address' => [],
            'landmark' => [],
            'date' => [],
            'status' => [],
            'start_time' => [],
            'end_time' => [],
            
        ]);

        $event = Event::where("id", $id)->first();

        if(empty($event)) return abort(400, "Data not found");

        $invitation = $event->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan event anda');
    
        // var_dump($data);die();
        try {
            $event->update($data);

            return response()->json($event);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'failed ',
                'detail' => $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::where("id", $id)->first();

        if(empty($event)) return abort(400, "Data not found");

        $invitation = $event->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan event anda');
        
        
        try {
            $event->delete();
            $response = [
                'message' => 'Item deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'failed ',
                'detail' => $e->errorInfo
            ]);
        }
    }

}
