<?php

namespace App\Http\Controllers;

use App\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
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
            $ubicacion = Ubicacion::join('zonas','ubicacions.zona_id','=','zonas.id')
            ->select('ubicacions.*','zonas.id as zona_id','zonas.nombre as zona_nombre')
            ->orderBy('zonas.nombre', 'asc')->paginate(10);
        }
        else{
            $ubicacion = Ubicacion::join('zonas','ubicacions.zona_id','=','zonas.id')
            ->select('ubicacions.*','zonas.id as zona_id','zonas.nombre as zona_nombre')
            ->where('ubicacions.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('zonas.nombre', 'asc')->paginate(10);
        }
       return [
            'pagination' => [
                'total'        => $ubicacion->total(),
                'current_page' => $ubicacion->currentPage(),
                'per_page'     => $ubicacion->perPage(),
                'last_page'    => $ubicacion->lastPage(),
                'from'         => $ubicacion->firstItem(),
                'to'           => $ubicacion->lastItem(),
            ],
            'ubicacion' => $ubicacion
        ];
    }

    public function selectUbicacion(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $criterio = $request->zona_id;
        $ubicacion=Ubicacion::where('zona_id','=',$criterio)->orderBy('nombre','ASC')->get();
        return ['ubicacion' => $ubicacion];
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
            'nombre' => 'bail|required|min:3|max:45|unique:zonas',
            'zona_id' => 'required|integer|min:1'
        ],
        ['incidente_id.min'=> 'Seleccione una :attribute']);
        $ubicacion=new Ubicacion();
        $ubicacion->nombre=$request->nombre;
        $ubicacion->zona_id=$request->zona_id;
        $ubicacion->estado=$request=1;
        $ubicacion->user_id=\Auth::user()->id;
        $ubicacion->save();
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ubicacion  $ubicacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:45', 'unique:zonas,nombre,'.request()->get("id")],
            'zona_id' => 'required|integer|min:1'
        ],
        ['zona_id.min'=> 'Seleccione una :attribute']);

        $ubicacion=Ubicacion::findOrFail($request->id);
        $ubicacion->nombre=$request->nombre;
        $ubicacion->zona_id=$request->zona_id;
        $ubicacion->estado=$request=1;
        $ubicacion->user_id=\Auth::user()->id;
        $ubicacion->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ubicacion=Ubicacion::findOrFail($request->id);
        $ubicacion->estado=$request=1;
        $ubicacion->user_id=\Auth::user()->id;
        $ubicacion->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ubicacion=Ubicacion::findOrFail($request->id);
        $ubicacion->estado=$request=0;
        $ubicacion->user_id=\Auth::user()->id;
        $ubicacion->save();
    }
}
