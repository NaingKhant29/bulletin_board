<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class PostController extends Controller
{
 public function index()
 {
 return "Controller - Post List";
 }
 public function detail($id)
 {
 return "Controller - Post Detail - $id";
 }
}
