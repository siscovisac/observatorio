<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        
        if ($buscar==''){
            $user = User::join('rols','users.rol_id','=','rols.id')
                ->select('users.id','users.name','users.password','users.email','rols.nombre',
                 'rols.id as rol_id','users.estado')
                ->orderBy('users.name', 'asc')->paginate(8);
        }
        else{
            $user = User::join('rols','users.rol_id','=','rols.id')
                ->join('personals','users.personal_id','=','personals.id')
                ->select('users.id','users.name','users.password','users.email','rols.nombre',
                 'rols.id as rol_id','users.estado')
                 ->where('users.name', 'like', '%'. $buscar . '%')
                 ->orderBy('users.name', 'asc')->paginate(8);
        }
       return [
            'pagination' => [
                'total'        => $user->total(),
                'current_page' => $user->currentPage(),
                'per_page'     => $user->perPage(),
                'last_page'    => $user->lastPage(),
                'from'         => $user->firstItem(),
                'to'           => $user->lastItem(),
            ],
            'user' => $user
        ];
    }

    public function getAuthUser(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user_id=\Auth::user()->id;
        $user = User::join('rols','users.rol_id','=','rols.id')
                ->select('users.*','rols.nombre','rols.id as rol_id')
                ->where('users.id','=', $user_id)->first();
        return $user;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'name' => 'bail|required|min:5|max:20|unique:users',
            'password'=>'bail|required|min:5',
            'email'=>'required|email|min:5|max:50|unique:users',
            'rol_id'=>'required|integer|min:1'
        ],
            [
                'rol_id.min'=>'Seleccione un Rol.'
            ]
        );
        $user = new User();
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->rol_id = $request->rol_id;
        $user->estado = '1';
        $user->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'name' => ['bail','required','min:5','max:20','unique:users,name,'.request()->get("id")],
            'password'=>'bail|required|min:5',
            'email'=>['required','email','min:5','max:50','unique:users,email,'.request()->get("id")],
            'rol_id'=>'required|integer|min:1'
        ],
            [
                'rol_id.min'=>'Seleccione un Rol.'
            ]
        );
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->rol_id = $request->rol_id;
        $user->estado = '1';
        $user->save();
    }

    public function perfilUpdate(Request $request, User $user)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'name' => ['bail','required','min:5','max:20','unique:users,name,'.request()->get("id")],
            'password'=>'bail|required|min:5',
            'email'=>['required','email','min:5','max:50','unique:users,email,'.request()->get("id")]
        ]);
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->estado = '1';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->estado = '1';
        $user->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->estado = '0';
        $user->save();
    }
}
