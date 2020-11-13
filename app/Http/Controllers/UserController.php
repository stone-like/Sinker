<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEntireDataRequest;
use App\Http\Requests\GetRecentRequest;
use App\Response\User\GetEntireResponse;
use App\UseCase\User\GetEntireDataUseCase;
use App\UseCase\User\GetRecentUseCase;
use App\User;
use App\Model\Like;
use App\Model\Reply;
use App\Model\Question;
use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

//これをしっかり入れる

class UserController extends Controller
{
    /**
     * @var GetEntireDataUseCase
     */
    private $getEntireDataUseCase;
    /**
     * @var GetRecentUseCase
     */
    private $getRecentUseCase;

    public function __construct(GetEntireDataUseCase $getEntireDataUseCase, GetRecentUseCase $getRecentUseCase, $fire = true)
    {
        if ($fire) {
            $this->fireMiddleware();
        }

        $this->getEntireDataUseCase = $getEntireDataUseCase;
        $this->getRecentUseCase = $getRecentUseCase;
    }

    public function getEntireData(int $id, GetEntireDataRequest $request)
    {
        return GetEntireResponse::transform($this->getEntireDataUseCase->execute($id));


    }

    public function getRecent(int $id, GetRecentRequest $request)
    {

        return $this->getRecentUseCase->execute($id);

    }

    public function updateUser(UpdateRequest $request)
    {
        $user = Auth::user();
        //いろいろconfirmationとか大丈夫だったらきちんとpreviousも同じか確認する
        // dump($request->password_previous);
        // dump($user->password);

        // dump(Hash::check($request->password_previous, $user->password));


        if (Hash::check($request->password_previous, $user->password)) {

            $user->update(['name' => $request->name, 'email' => $request->email, 'password' => $request->password]);

        } else {
            $response['errors']['previous'] = "Dont't match your previous_password";
            return response()->json($response, 422);
        }

        return $user;

    }

    public function updateImage(ImageRequest $request)
    {
        //画像をpublic/images/に移す

        $user = Auth::user();
        //  dump($request);
        //  dump($request->image);

        //  dump(str_shuffle(time().$request->image->getClientOriginalName()). '.' .$request->image->getClientOriginalExtension());

        if ($user->img_path != public_path('images') . "\\sks.jpeg") {
            //もし二回目以降の変更ならpublic以下にある画像を削除

            $file_name = basename($user->img_path);
            dump($file_name);
            dump('\\public\\images' . "\\" . $file_name);
            File::delete(public_path('images') . "\\" . $file_name);
        }

        $imageName = str_shuffle(time() . $request->image->getClientOriginalName()) . '.' . $request->image->getClientOriginalExtension();
        //ランダムにするためshuffleして、でそのあとにもともとの拡張子をくっつけている

        $request->image->move(public_path('images'), $imageName);
        //これでC:\xxxxx\projectName\public\images\ファイル名がつくれた
        //  dump($user->img_path)
        $file_path = public_path('images') . "\\" . $imageName;//これをデータベースに入れる

        $user->update(['img_path' => $file_path]);


        return $file_path;
    }

    public function fireMiddleware(): void
    {
        $this->middleware('JWT', ['except' => ['index', 'show']]);
    }
}
