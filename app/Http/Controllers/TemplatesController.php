<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\Titem;
use App\Tchecklist;
use Illuminate\Support\Facades\DB;

class TemplatesController extends Controller
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

    public function index() 
    {
        $template = collect(Template::paginate(10));
        
        return response()->json([

            'meta' => [
                'count' => $template['per_page'],
                'total' => $template['total']
            ],
            'links' => [
                'first' => $template['first_page_url'],
                'last' => $template['last_page_url'],
                'next' => $template['next_page_url'],
                'prev' => $template['prev_page_url']
            ],
            'data' => [
                'name' => $template['data'],                        
            ]
        ]);

    }

    public function store(Request $request)
    {


        $data = collect($request->input('items'))->all();

        $template = Template::create([
            'name' => $request->input('name')
        ]);

        $template->checklist()->create([
            'description' => $request->input('list_desc'),
            'due_interval' => $request->input('list_interval'),
            'due_unit' => $request->input('list_unit')
        ]);
    
        foreach($data as $value)  {  
        $template->items()->create($value);
        }
      
        return response()->json([
            'data' => [
                'id' => $template->id,
                'attributes' => [
                    'name'  => $template->name,
                    'checklist' => [
                        'description' => $template->checklist->description,
                        'due_interval' => $template->checklist->due_interval,
                        'due_unit' => $template->checklist->due_unit,
                    ],
                    'items' => $data
                ]
            ]
        ], 201);       
    }

    public function show($templateId)
    {
        $template = Template::find($templateId);

        return response()->json([
            'data' => [
                'type' => 'templates',
                'id' => $template->id,
                'attributes' => [
                    'name' => $template->name,
                    'items' => $template->items->toArray(),
                    'checklist' => [
                        'due_unit' => $template->checklist->due_unit,
                        'description' => $template->checklist->description,
                        'due_interval' => $template->checklist->due_interval
                    ]
                ],
                'links' => [
                    'self' => url()."/templates/".$template->id
                ]
            ]
        ]);
    }

    public function update(Request $request, $templateId)
    {

        $data = collect($request->input('items'))->all();

        $template = Template::find($templateId);

        if($request->has('name'))
        {
            $template->update(['name' => $request->input('name')]);
        }
        elseif($request->has('list_desc')){
            $template->checklist->update([
                'description' => $request->input('list_desc'),               
            ]);
        }
        elseif($request->has('list_interval')){
            $template->checklist->update([
                'due_interval' => $request->input('list_interval'),               
            ]);
        }
        elseif($request->has('list_unit')){
            $template->checklist->update([
                'due_unit' => $request->input('list_unit'),               
            ]);    
        }        
      
        return response()->json([
            'data' => [
                'id' => $template->id,
                'attributes' => [
                    'name'  => $template->name,
                    'checklist' => [
                        'description' => $template->checklist->description,
                        'due_interval' => $template->checklist->due_interval,
                        'due_unit' => $template->checklist->due_unit,
                    ],
                    'items' => $template->items->toArray()
                ]
            ]
        ], 200);  
    }

    public function destroy($templateId)
    {
        Template::find($templateId)->delete();
        
        return response(204);
    }

    
}
