<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\VsVideos;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*Carga la página de inicio del objeto*/
        $vs_videos = VsVideos::where('status','=',1)->get();
        $cont = VsVideos::count();
        $videos = $this->cargarDT($vs_videos );
        return  view('videos.index')->with('videos',$videos)->with('cont', $cont);

    }
    public function cargarDT($consulta)
    {
        $videos = [];

        foreach ($consulta as $key => $value){

            $ruta = "eliminar".$value['id'];
            $eliminar = route('delete-video', $value['id']);
            $actualizar =  route('videos.edit', $value['id']);

            $acciones = '
                <div class="btn-acciones">
                    <div class="btn-circle">
                        <a href="'.$actualizar.'" role="button" class="btn btn-success" title="Actualizar">
                            <i class="far fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#'.$ruta.'">
                        <i class="far fa-trash"></i>
                        </button>

                    </div>
                </div>

                 <!-- Modal -->
            <div class="modal fade" id="'.$ruta.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">¿Seguro que deseas eliminar este video?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-primary">
                        <small>
                            '.$value['id'].', '.$value['title'].'                 </small>
                      </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                      <a href="'.$eliminar.'" type="button" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>

            ';

            $videos[$key] = array(
                $acciones,
                $value['id'],
                $value['title'],
                $value['description'],
                $value['image'],
                $value['video_path'],
                $value['name'],
                $value['email']

            );

        }

        return $videos;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //solamenete abre el formulario para insertar(capturar)
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //guarda el registro capturado(no editado, sino es nuevo)
        $validateData = $this->validate($request,[
           'title'=> 'required|min:5',
           'description'=> 'required',
           'video'=> 'mimes:mp4'
        ]);
        $video = new Video();
        $user = \Auth::user();
        $video->user_id = $user->id;
        $video->title = $request->input('title');
        $video->description = $request->input('description');
        //subir imagen miniatura
        $image = $request->file('image');
        if($image){
            $image_path = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_path,\File::get($image));
            $video->image = $image_path;
        }
        //subir video
        $video_file = $request->file('video');
        if ($video_file){
            $video_path = time().$video_file->getClientOriginalName();
            \Storage::disk('videos')->put($video_path,\File::get($video_file));
            $video->video_path = $video_path;
        }
        $video->save();
        return redirect()->route('videos.index')
        ->with(array(
            'message'=>'El video se ha subido correctamente'
    ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //muestra un registro individual
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //abre el formulario para edición de un registro
        $video = VsVideos::find($id);

        return view('videos.edit')->with('video', $video);
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
        //guarda la información modificada del edit
        $validateData = $this->validate($request,[
            'title'=> 'required|min:5',
            'description'=> 'required',
            'video'=> 'mimes:mp4'
        ]);
        $user = \Auth::user();
        $video = Video::find($id);
        $video->user_id = $user->id;
        $video->title = $request->input('title');
        $video->description = $request->input('description');
        //subir imagen miniatura
        $image = $request->file('image');
        if($image){
            $image_path = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_path,\File::get($image));
            $video->image = $image_path;
        }
        //subir video
        $video_file = $request->file('video');
        if ($video_file){
            $video_path = time().$video_file->getClientOriginalName();
            \Storage::disk('videos')->put($video_path,\File::get($video_file));
            $video->video_path = $video_path;
        }
        $video->update();
        return redirect()->route('videos.index')
            ->with(array(
                'message'=>'El video se ha subido correctamente'
            ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //borrado
    }

    public function delete_video($video_id){
        $video = Video::find($video_id);
        if($video){
            $video->status = 0;
            $video->update();
            return redirect()->route('videos.index')->with(array(
                "message" => "El video se ha eliminado correctamente"
            ));
        }else{
            return redirect()->route('videos.index')->with(array(
                "message" => "El video que trata de eliminar no existe"
            ));
        }

    }
}
