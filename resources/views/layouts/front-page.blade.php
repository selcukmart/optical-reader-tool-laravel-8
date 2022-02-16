<?php
/**
 * @author selcukmart
 * 19.12.2021
 * 15:17
 */
require_once base_path() .'/bootstrap/mart-app.php';
?><!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
@include('includes.header')

<div class="container-fluid">
    <div class="row">
        @include('includes.menu')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @include('includes.content-top-bar')
            @yield('content')
        </main>
    </div>
</div>

@include('includes.footer')


</body>
</html>
