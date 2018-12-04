<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!-- <a href="http://handscube.local:8081/test">跳转</a> -->
    <form action="/test" method="post">
        <span>username:</span><input name ='username' type="text">
        <span>password:</span><input name='pwd' type="password">
        <input type="hidden" name="_method" value="PUT">
        <input type="submit" value='提交'>
    </form>
</body>
</html>
