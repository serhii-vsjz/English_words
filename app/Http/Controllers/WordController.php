<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Word;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
    }

    public function check(Request $request)
    {
        $word = Word::find($request['id']);

        if (strcasecmp($word->english, $request['english']))
        {
            $word->repit++;
            $word->save();
            $lastResult = false;
        }
        else {
            $word->repit--;
            $word->save();
            if ($word->repit == 0)
            {
                return $this->destroy($word->id);
            }
            $lastResult = true;
        }
        return redirect(route('show', ['lastId' => $word->id, 'lastResult' => $lastResult]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $request = request();
        $word = Word::all()->where('active','=', '1')->random();

        if ($request->has('lastId')) {

            $lastWord = Word::find($request['lastId']);

            return view('show', [
                'id' => $word->id,
                'russian' => $word->russian,
                'lastId' => $request['lastId'],
                'lastResult' => $request['lastResult'],
                'lastEnglish' => $lastWord->english,
                'lastRussian' => $lastWord->russian
            ]);
        } else {
            return view('show', [
                'id' => $word->id,
                'russian' => $word->russian,
                'lastId' => '',
                'lastResult' => $request['lastResult'],
                'lastEnglish' => '',
                'lastRussian' => ''
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $word = new Word();
        $word->english = $request['english'];
        $word->russian = $request['russian'];
        $word->repit = 10;
        $word->save();
        echo 'vipupu';
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::find($id);
        $word->active = false;
        $word->save();
        return redirect(route('show'))  ;
    }
}
