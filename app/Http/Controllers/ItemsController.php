<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Checklist;
use App\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class ItemsController extends Controller
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

    public function store(Request $request, $checklistId)
    {
        $user = Auth::user()->id;

        $checklist = Checklist::find($checklistId);

        Item::create([
            'name' => $request->input('name'),
            'user_id' => $user,
            'due' => $request->input('due'),
            'urgency' => $request->input('urgency'),
            'checklist_id' => $checklist->id,
            'assignee_id' => 123,
            'task_id' => '123'            
        ]);

        return response()->json([
            'data' => [
                'type' => 'checklists',
                'id' => $checklist->id,
                'attributes' => [
                    'description' => $checklist->description,
                    'is_completed' => $checklist->is_completed,
                    'completed_at' => $checklist->completed_at,
                    'due' => $checklist->due,
                    'urgency' => $checklist->urgency,
                    'updated_by' => $checklist->update_by,
                    'updated_at' => $checklist->updated_at,
                    'created_at' => $checklist->created_at,
                ],
                'links' => [
                    'self' => url()."/checklists/".$checklist->id
                ]
            ]
        ], 200);
    }

    public function show($itemsId) 
    {

       
        $item = Item::find($itemsId);

        return response()->json([

            'data' => [
                'type' => 'checklists',
                'id' => $item->checklist->id,
                'attributes' => [
                    'description' => $item->checklist->description,
                    'completed_at' => $item->checklist->completed_at,
                    'due' => $item->checklist->due,
                    'urgency' => $item->checklist->urgency,
                    'updated_by' => $item->checklist->updated_by,
                    'created_by' => $item->checklist->created_by,
                    'checklist_id' => $item->cheklist_id,
                    'assignee_id' => $item->assignee_id,
                    'task_id' => $item->task_id,
                    'created_at' => $item->checklist->created_at,
                    'updated_at' => $item->checklist->updated_at
                ],
                'links' => [
                    'self' => url()."/checklist/".$item->checklist->id
                ]
            ]
                ], 200);
    }

    public function update(Request $request,$checklistId, $itemsId) 
    {
       
        $checklist = Checklist::find($checklistId);

        $checklist->update([
            'description' => $request->input('description'),
            'urgency' => $request->input('urgency')            
        ]);

        $item = $checklist->items()->where('id', '=', $itemsId)->first();

        $item->update(
            [
                'assignee_id' => $request->input('assignee_id'),
                'due' => $request->input('due')
            ]);
           
      

        return response()->json([
            'data' => [
                'type' => 'checklists',
                'id' => $checklist->id,
                'attributes' => [
                    'description' => $checklist->description,
                    'is_completed' => $checklist->is_completed,
                    'due' => $checklist->due,
                    'urgency' => $checklist->urgency,
                    'assignee_id' => $item->assignee_id,
                    'completed_at' => $checklist->completed_at,
                    'updated_by' => $checklist->updated_by,
                    'updated_at' => $checklist->updated_at,
                    'created_at' => $checklist->created_at
                    
                ],
                'links' => [
                    'self' => url()."/checklist/".$checklist->id
                ]
            ]
        ], 200);
        
    }

    public function destroy($checklistId, $itemsId){

        $item = Checklist::find($checklistId)->items()->where('id', '=', $itemsId)->first();

        $item->delete();

        return response(null, 200);
    }

    public function complete(Request $request){

        $items = collect($request->input('item_id'))->all();
       
        foreach ($items as $value){

            $data = Item::find($value)->update(['is_completed' => true]);
            
        }

        $collection = Item::whereIn('id', $items)->get();
            return response()->json([
                'data' => $collection->sortKeys()
            ]);
        
    }

    public function incomplete(Request $request)
    {
        $items = collect($request->input('item_id'))->all();
       
        foreach ($items as $value){

            $data = Item::find($value)->update(['is_completed' => false]);
            
        }

        $collection = Item::whereIn('id', $items)->get();
            return response()->json([
                'data' => $collection->sortKeys()
            ]);
    }

    public function allitems($checklistId)
    {
        $checklist = Checklist::find($checklistId);

        $items = $checklist->items->toArray();

        return response()->json([
            'data' => [
                'type' => 'checklists',
                'id' => $checklist->id,
                'attributes' => [
                    'object_domain' => $checklist->object_domain,
                    'object_id' => $checklist->object_id,
                    'description' => $checklist->description,
                    'is_completed' => $checklist->is_completed,
                    'last_update_by' => $checklist->updated_by,
                    'updated_at' => $checklist->updated_at,
                    'created_at' => $checklist->created_at
                ],
                'items' => [
                    $items
                ],
                'links' => [
                    'self' => url()."/checklists/".$checklist->id
                ]
            ]
        ]);
    }

    public function bulk(Request $request)
    {
        $bulk = $request->all();
       
    }
}
