<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;


class MessageController extends Controller
{
    /**
    * @OA\Post(
    *   path="/api/send-message",
    *   summary="Send message",
    *   description="Message sending",
    *   tags={"message"},
    *  @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    *   @OA\RequestBody(
    *       required=true,
    *       description="Send message",
    *       @OA\JsonContent(
    *           required={"contact","message","sender_id"},
    *           @OA\Property(property="contact", type="string", example="+90 545 386 83 09"),
    *           @OA\Property(property="message", type="text", format="text"),
    *           @OA\Property(property="sender_id", type="integer", format="user_id"),
    *       )
    *   )
    * )
    */

    public function sendMessage(Request $request){

        $request->validate([
            'contact' => 'required|string',
            'message' => 'required|text'
        ]);

        $message = new Message([
            'contact' => $request->contact,
            'message' => $request->message,
            'sender_id' => $request->user()->id
        ]);

        $message->save();

        return response()->json([
            'message' => $message,
            'status' => "message sent successful !"
        ], 201);
    }

    /**
    * @OA\Get(
    *   path="/api/list-message-sent",
    *   summary="Get messages",
    *   description="Get all messages sent",
    *   tags={"message"},
    *  @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    *   @OA\RequestBody(
    *       required=true,
    *       description="Get all message",
    *   )
    * )
    */
    public function listMessageSent(){
        $messages = Message::orderBy('send_time','DESC')->get();
        return response()->json([
            'messages' => $messages,
            ], 201);
    }

    /**
    * @OA\Get(
    *   path="/api/list-message-sent-by-date/{date}",
    *   summary="Get messages",
    *   description="Get all messages sent on one date",
    *   tags={"message"},
    *    * @OA\Parameter(
    *      name="date",
    *      in="path",
    *      required=true,
    *      @OA\Schema(
    *           type="date"
    *      )
    *   ),
    *   @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    * )
    */

    public function listMessageSentByDate($date){
        $Date = Carbon::createFromFormat('d-m-Y', $date);

        $messages = Message::whereDate('send_time',$Date)
                            ->orderBy('send_time','DESC')
                            ->get();
        return response()->json([
            'messages' => $messages,
            ], 201);
    }

    /**
    * @OA\Get(
    *   path="/api/get-message-details/{id}",
    *   summary="Get one message details",
    *   description="Get all details for one message",
    *   tags={"message"},
    *    * @OA\Parameter(
    *      name="id",
    *      in="path",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *       ),
    * )
    */
    public function getMessageDetail($id){
        $message = Message::find($id);
        return response()->json([
            'message' => $message,
            ], 201);
    }
}
