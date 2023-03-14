<?php

namespace App\Http\Controllers;


use App\Http\Resources\InvitationResource;
use App\Models\Event;
use App\Models\Gift;
use App\Models\Invitation;
use App\Models\Message;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HttpRequest $request)
    {
        // $items = Invitation::orderBy('created_at', 'desc')->get();
        
        $items = Auth::user()->invitations;
        // $user = $request->user();

        // var_dump($user);
        return InvitationResource::collection($items);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessages(HttpRequest $request, $id)
    {
        $messages = Message::where('invitation_id', $id)->paginate();
        
        return InvitationResource::collection($messages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBySlug(HttpRequest $request, $slug)
    {
        try {
            $invitation = Invitation::where('path', $slug)->first();
            $invitation['events'] = Event::where('invitation_id', $invitation->id)->get();
            $invitation['gifts'] = Gift::where('invitation_id', $invitation->id)->get();
            
            return response()->json($invitation);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Item not found '
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(HttpRequest $request, $id)
    {
        try {
            $res = [];
            if(!is_numeric($id)){
                $res = Invitation::where('user_id', Auth::user()->id)->first();
            } else $res = Invitation::findOrFail($id);
            
            if(isset($res[$id])) return response()->json($res[$id]);
            
            $res['events'] = $res->events;
            $res['gifts'] = $res->gifts;

            return response()->json($res);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Item not found '
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        $data = $request->all();
        $validate = $this->validate($request, [
            'title' => ['required'],
            'male_name' => ['required'],
            'male_father_name' => ['required'],
            'male_mother_name' => ['required'],
            'female_name' => ['required'],
            'female_father_name' => ['required'],
            'female_mother_name' => ['required'],
        ]);
        
        // // Check if image was given and save on local file system
        if (isset($data['male_foto'])) {
            $relativePath  = $this->saveImage($data['male_foto']);
            $data['male_foto'] = $relativePath;
        }

        if (isset($data['female_foto'])) {
            $relativePath  = $this->saveImage($data['female_foto']);
            $data['female_foto'] = $relativePath;
        }

        $data['user_id'] = Auth::user()->id;
        try {
            $invite = Invitation::create($data);
            return new InvitationResource($invite);
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
        $item = Invitation::where([["id", $id],["user_id", Auth::user()->id]])->first();

        if(empty($item)) return abort(400, "Data not found");

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 402);
        }

        if (isset($input['male_foto'])) {
            $relativePath  = $this->saveImage($input['male_foto']);
            $input['male_foto'] = $relativePath;
        }

        if (isset($input['female_foto'])) {
            $relativePath  = $this->saveImage($input['female_foto']);
            $input['female_foto'] = $relativePath;
        }

        try {
            $item->update($input);

            return new InvitationResource($item);
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
        $item = Invitation::where([["id", $id],["user_id", Auth::user()->id]])->first();
        if(empty($item)){
            return abort(400, "Data not found");
        }
        try {
            $item->delete();
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

    private function saveImage($img){
        $base64_image = $img;
        $url = '';
        //decode base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
        
            $data = base64_decode($data);
            
            $safeName = 'uploads/images/'.md5($img).".jpg";
            // file_put_contents($safeName, $data);
            Storage::disk('public')->put($safeName, $data);
            $url = Storage::url($safeName);
            // $url = "maman";
        }
        return $url;
    }

    //
}
