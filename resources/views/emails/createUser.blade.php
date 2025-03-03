@if(isset($name))
    <p>Hello {{ $name }}</p>
@endif
@if(isset($url))
    <p>In <a href="{{ $url }}">Creos Server</a></p>
@endif
@if(isset($email))
    <p>Account created with login {{ $email }}</p>
@endif
@if(isset($password))
    <p>and password  {{ $password }}</p>
@endif
