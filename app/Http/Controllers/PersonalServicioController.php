<?php

namespace App\Http\Controllers;

use App\PersonalServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PersonalStoreRequest;
use App\Http\Requests\PersonalUpdateRequest;
class PersonalServicioController extends Controller
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
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $personal = PersonalServicio::join('cargos','personal_servicios.cargo_id','=','cargos.id')
                ->select('personal_servicios.*','cargos.nombre as cargo_nombre')
                ->orderBy('personal_servicios.id', 'desc')->paginate(10);
        }
        else{
            $personal = PersonalServicio::join('cargos','personal_servicios.cargo_id','=','cargos.id')
                ->select('personal_servicios.*','cargos.nombre as cargo_nombre')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('personal_servicios.id', 'desc')->paginate(10);
        }
       return [
            'pagination' => [
                'total'        => $personal->total(),
                'current_page' => $personal->currentPage(),
                'per_page'     => $personal->perPage(),
                'last_page'    => $personal->lastPage(),
                'from'         => $personal->firstItem(),
                'to'           => $personal->lastItem(),
            ],
            'personal' => $personal
        ];
    }

    public function listarPersonalServicio(){
        $personalServicio = PersonalServicio::select('id','apellidos', DB::raw("CONCAT(apellidos,' ',nombres) as personal_nombre"))
        ->whereNotIn('cargo_id', [2, 6])
        ->where('estado','=',1)->get();
        return ['personalServicio' => $personalServicio];
    }
    
    public function listarPersonal(){
        $personalServicio = PersonalServicio::select('id','apellidos', DB::raw("CONCAT(apellidos,' ',nombres) as personal_nombre"))
        ->whereNotIn('cargo_id', [2, 6])
        ->where('estado','=',1)->get();
        return ['personalServicio' => $personalServicio];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalStoreRequest $request)
    {
        if (!$request->ajax()) return redirect('/');
        $personal = new PersonalServicio();
        $personal->apellidos = $request->apellidos;
        $personal->nombres = $request->nombres;
        $personal->dni = $request->dni;
        $personal->fechaNacimiento = $request->fechaNacimiento;
        $personal->direccion = $request->direccion;
        $personal->telefono = $request->telefono;
        $personal->correo = $request->correo;
        $personal->fechaIngreso = $request->fechaIngreso;
        $personal->grupo = $request->grupo;
        $personal->cargo_id = $request->cargo_id;
        $personal->fechaCese = $request->fechaCese;
        $personal->observacion = $request->observacion;
        $personal->estado = $request='1';
        $personal->user_id=\Auth::user()->id;
        $personal->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PersonalServicio  $personalServicio
     * @return \Illuminate\Http\Response
     */
    public function update(PersonalUpdateRequest $request, PersonalServicio $personal)
    {
        if (!$request->ajax()) return redirect('/');
        $personal = PersonalServicio::findOrFail($request->id);
        $personal->apellidos = $request->apellidos;
        $personal->nombres = $request->nombres;
        $personal->dni = $request->dni;
        $personal->fechaNacimiento = $request->fechaNacimiento;
        $personal->direccion = $request->direccion;
        $personal->telefono = $request->telefono;
        $personal->correo = $request->correo;
        $personal->fechaIngreso = $request->fechaIngreso;
        $personal->grupo = $request->grupo;
        $personal->cargo_id = $request->cargo_id;
        $personal->fechaCese = $request->fechaCese;
        $personal->observacion = $request->observacion;
        $personal->estado = $request='1';
        $personal->user_id=\Auth::user()->id;
        $personal->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $personal = PersonalServicio::findOrFail($request->id);
        $personal->estado = $request='1';
        $personal->user_id=\Auth::user()->id;
        $personal->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $personal = PersonalServicio::findOrFail($request->id);
        $personal->estado = $request='0';
        $personal->user_id=\Auth::user()->id;
        $personal->save();
    }
}
