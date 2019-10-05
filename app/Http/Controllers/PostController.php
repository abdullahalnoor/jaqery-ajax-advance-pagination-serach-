<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    
    public function master(){
        return view('post.master');
    }

    public function index( $search= null){
     
        if($search){
            $posts =  Post::where('name','LIKE', "%$search%")->orderBy('id','desc')->paginate(5);
        }else{
            $posts =  Post::orderBy('id','desc')->paginate(5);
        }
       return $posts ;
        
    }
    public function create(){
        return view('post.create');        
    }

    public function store(Request $request){
        $post =  new Post();
        $post->name = $request->name;
        $post->title = $request->title;
        $post->save();
        return $post;
        return back();
    }

    public function excel(){
        $posts =  Post::take(5)->get(['name'])->toArray();
        $fp = fopen('file.csv', 'w');
        
        foreach ($posts as $fields) {
            // return $fields['name'];
            fputcsv($fp, $fields);
        }
        fclose($fp);
        if (file_exists('file.csv')) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename('file.csv').'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize('file.csv'));
            readfile('file.csv');
            exit;
        }
       

    }

   

}
