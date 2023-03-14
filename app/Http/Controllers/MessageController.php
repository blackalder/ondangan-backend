<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Invitation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
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
            'invitation_id' => ['required'],    
            'name' => ['required'],
            'message' => ['required'],
        ]);

        $item = Invitation::where([["id", $data['invitation_id']]])->first();
        
        if(empty($item)) abort(400, 'data not found');

        try {
            $invite = Message::create($data);
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
            'name' => ['required'],
            'message' => ['required'],
        ]);

        $Message = Message::where("id", $id)->first();

        if(empty($Message)) return abort(400, "Data not found");

        $invitation = $Message->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan Message anda');
    
        try {
            $Message->update($data);

            return response()->json($data);
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
        $Message = Message::where("id", $id)->first();

        if(empty($Message)) return abort(400, "Data not found");

        $invitation = $Message->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan Message anda');
        
        
        try {
            $Message->delete();
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
