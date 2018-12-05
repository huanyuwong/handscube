<?php

namespace App\Controllers;

use App\Models\Post;
use Handscube\Kernel\Controller;
use Handscube\Kernel\Request;
use Handscube\Kernel\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Handscube\Kernel\Response
     */
    public function index()
    {
        //
        // return "index";
        return $this->app->request;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Handscube\Kernel\Response
     */
    public function create()
    {
        //
        return new View('post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Handscube\Kernel\Request  $request
     * @return \Handscube\Kernel\Response
     */
    public function store(Request $request)
    {
        return $this->app->request->files;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Handscube\Kernel\Response
     */
    public function show(Request $request, $id)
    {
        //
        return $request->only('gid', 'id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Handscube\Kernel\Response
     */
    public function edit($id)
    {
        //
        return "edit $id";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Handscube\Kernel\Request  $request
     * @param  int  $id
     * @return \Handscube\Kernel\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Handscube\Kernel\Response
     */
    public function destroy($id)
    {
        //
        return "delete $id";
    }
}
