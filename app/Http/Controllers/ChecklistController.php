<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Checklist;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $checklists = Checklist::paginate(10); 
        
        return response()->json([

            'meta' => [
                'count' => $checklists->perPage(),
                'total' => $checklists->count()
            ],
            'links' => [
                'first' => url($checklists->onFirstPage()),
                'last' => url($checklists->lastPage()),
                'next' => $checklists->nextPageUrl(),
                'prev' => $checklists->previousPageUrl()
            ],
            'data' => [
                'type' => 'checklists',
                'id' => "1",
                'attributes' => [                   
                    $checklists            
                ],
                'links' => [
                    'self' => url()."/api/v1/checklists/".$checklists
                ]
            ]
        ]);

    }

    public function show($checklistId) {

        $checklist = Checklist::find($checklistId);

        if ($checklist) {

            return response()->json([
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => [
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'description' => $checklist->description,
                        'is_completed' => $checklist->is_completed,
                        'due' => $checklist->due,
                        'urgency' => $checklist->urgency,
                        'completed_at' => $checklist->completed_at,
                        'last_updated_by' => $checklist->updated_by,
                        'updated_at' => $checklist->updated_at,
                        'created_at' => $checklist->created_at
                    ],
                    'links' => [
                        'self' => url()."/checklists/".$checklist->id
                    ]
                ]
            ]);
        }
        elseif (!$checklist) {
            
            return response()->json([
                'status' => '404',
                'error' => 'Not Found'
            ]);
        }
        else {

            return response()->json([
                'status' => '500',
                'error' => 'Server Error'
            ]);
        }
    }

    public function store(Request $request){

        $items = [
            "Visit his house",
            "Capture a photo",
            "Meet him on the house"
        ];

        $task_id = "123";

        $user = Auth::check();
 
        if($user){

            $checklist = Checklist::create([
                'object_domain' => $request->input('object_domain'),
                'object_id' => $request->input('object_id'),
                'description' => $request->input('description'),
                'due' => $request->input('due'),
                'urgency' => $request->input('urgency'),
            ]); 

            return response()->json([
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => [
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'task_id' => $task_id,
                        'description' => $checklist->description,
                        'is_completed' => $checklist->is_completed,
                        'due' => $checklist->due,
                        'urgency' => $checklist->urgency,
                        'completed_at' => $checklist->compeletd_at,
                        'updated_by' => $checklist->updated_by,
                        'created_by' => $checklist->created_by,
                        'created_at' => $checklist->created_at,
                        'updated_at' => $checklist->updated_at
                    ],
                    'links' => [
                        'self' => url()."/api/v1/checklists/".$checklist->id
                    ]
                ]
            ], 201);
        }
        elseif (!$user) {
           
            return response()->json([
                'status' => '401',
                'error' => 'Not Authorized'
            ], 401);
        }
        else {

            return response()->json([
                "status" => "500",
                "error" => "Server Error"
            ], 500);
        }
    }

    public function update(Request $request, $checklistId) {

        $checklist = Checklist::find($checklistId);
        
        $user = Auth::check();

        if($checklist && $user) {

            $checklist->update($request->all());

            return response()->json([
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => [
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'description' => $checklist->description,
                        'is_completed' => $checklist->is_completed,
                        'completed_at' => $checklist->completed_at,
                        'created_at' => $checklist->created_at                        
                    ],
                    'links' => [
                        'self' => url()."/checklists/".$checklist->id
                    ]
                ]
            ]);
        }
        elseif (!$checklist && $user) {
            return response()->json([
                'status' => '404',
                'error' => 'Not Found'
            ]);
        }
        elseif(!$user){
            return response()->json([
                'status' => '401',
                'error' => 'Not Authorized'
            ], 401);
        }
        else {
            return response()->json([
                "status" => "500",
                "error" => "Server Error"
            ], 500);
        }

    }

    public function destroy($checklistId){

        $checklist = Checklist::find($checklistId);

        $user = Auth::check();

        if($checklist && $user){
            $checklist->delete();

            return response('no content', 204);
        }

        elseif (!$checklist && $user) {
            return response()->json([
                'status' => '404',
                'error' => 'Not Found'
            ]);
        }
        elseif(!$user){
            return response()->json([
                'status' => '401',
                'error' => 'Not Authorized'
            ], 401);
        }
        else {
            return response()->json([
                "status" => "500",
                "error" => "Server Error"
            ], 500);
        }

    }
}
