<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
 <meta charset="utf-8">
 <title>Post List</title>
</head>
<body>
 <h1>Post List</h1>
 <ul>
 @foreach($posts as $post)
 <li>{{ $post['title'] }}</li>
 @endforeach
 </ul>
</body>
</html>
