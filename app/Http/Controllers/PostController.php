<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
     $data = Post::latest()->paginate(5);
     return view('posts.index', [
     'posts' => $data
     ]);
    }
    
 public function detail($id)
 {
 return "Controller - Post Detail - $id";
 }
}
