@extends('layouts.app_base')

@section('submit')
<?php
    if ( ! isset( $_COOKIE['depr_poles'] ) || ! isset( $_COOKIE['dest_poles'] ) ) {
       header("Location: http://localhost/submit");
       exit;
    }
?>
@endsection

@section('content')
<div id="app">
 <header-component></header-component>
</div>
@endsection