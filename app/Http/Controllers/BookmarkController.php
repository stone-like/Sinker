<?php

namespace App\Http\Controllers;

use App\Model\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookmarkResource;
use Symfony\Component\HttpFoundation\Response;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //今ログインしているユーザーのbookmarkをget
        $user = Auth::user();


        // dump(Bookmark::where('user_id', $user->id)->get());
        return BookmarkResource::collection(Bookmark::where('user_id', $user->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //bookmarksにここで登録したいのはuser_idと$request->name
        $bookmark = auth()->user()->bookmark()->create(["name" => $request->name]);

        return new BookmarkResource($bookmark);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show(Bookmark $bookmark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        $bookmark->Delete();
        return response('Deleted', Response::HTTP_NO_CONTENT);
    }
}
