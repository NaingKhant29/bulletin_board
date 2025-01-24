<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class PostController extends Controller
{
    public function index()
    {
     $data = [
     [ "id" => 1, "title" => "First Post" ],
     [ "id" => 2, "title" => "Second Post" ],
     ];
     return view('posts.index', [
     'posts' => $data
     ]);
    }
    
 public function detail($id)
 {
 return "Controller - Post Detail - $id";
 }
}