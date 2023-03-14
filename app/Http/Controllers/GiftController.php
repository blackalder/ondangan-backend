<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Invitation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GiftController extends Controller
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
            'type' => ['required','in:bank,ewalet'],
            'name' => ['required'],
            'bank_account' => [],
        ]);

        $item = Invitation::where([["user_id", Auth::user()->id]])->first();
        
        if(empty($item)) abort(400, 'data not found');

        $data['invitation_id'] = $item['id'];

        try {
            $invite = Gift::create($data);
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
            'type' => ['required'],
            'name' => ['required'],
            'bank_account' => [],
        ]);

        $item = Gift::where("id", $id)->first();

        if(empty($item)) return abort(400, "Data not found");

        $invitation = $item->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan event anda');

        try {
            $item->update($data);

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
        $Gift = Gift::where("id", $id)->first();
        
        if(empty($Gift)) return abort(400, "Data not found");

        $invitation = $Gift->invitation;
        if($invitation->user_id != Auth::user()->id) abort(403, 'Bukan Gift anda');
        
        
        
        try {
            $Gift->delete();
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
