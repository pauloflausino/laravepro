<?php

namespace App\Http\Controllers\dtable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use Redirect, Response;

class AjaxCrudController extends Controller
{
    public function index()
    {
        if(request()->ajax()){
            return datatables()->of(Post::latest()->get())
                ->addColumn('action', function($data){
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0)" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger"> Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dtable.index');
    }

    public function store(Request $request)
    {
        $postId = $request->post_id;
        $post = Post::updateOrCreate(['id' => $postId],
                ['title' => $request->title, 'body' => $request->body]);
        return Response::json($post);
        
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $post = Post::where($where)->first();

        return Response::json($post);
    }

    public function destroy($id)
    {
        $post = Post::where('id',$id)->delete();

        return Response::json($post);
    }
}
