<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Categoria;

class PeliculaController extends Controller
{
    public function index()
    {
        $peliculas = Pelicula::with('categoria')->get();

        return response()->json([
            "mensaje" => $peliculas->isEmpty() ? "No existen películas registradas" : "Películas cargadas",
            "datos" => $peliculas
        ], 200);
    }

    public function indexDisponibles(){
        $peliculas = Pelicula::where('is_deleted', false)->get();
        return response()->json(["mensaje"=>"Películas disponibles cargadas","datos"=>$peliculas ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required|string|max:255",
            "descripcion" => "required|string",
            "precio" => "required|numeric|min:0",
            "imagen" => "required|mimes:png,jpg,jpeg|max:2048",
            "categoria" => "required|exists:categorias,id",
        ], [
            "nombre.required" => "El título es requerido para registrar la película",
            "descripcion.required" => "La descripción es requerida para registrar la película",
            "precio.required"  => "El precio es requerido para registrar la película",
            "precio.numeric" => "El precio debe ser un número válido",
            "precio.min" => "El precio no puede ser negativo",
            "imagen.required" => "La imagen es requerida para registrar la película",
            "imagen.mimes" => "Formato de la imagen no permitido, formatos permitidos: PNG, JPG y JPEG",
            "imagen.max" => "El tamaño de la imagen no debe superar los 2MB",
            "categoria.required" => "La categoría es requerida para registrar la película",
            "categoria.exists" => "La categoría seleccionada no es válida"
        ]);

        $pelicula = new Pelicula();
        $pelicula->nombre = $request->nombre;
        $pelicula->descripcion = $request->descripcion;
        $pelicula->precio = $request->precio;
        $pelicula->categoria_id = $request->categoria;

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre_imagen = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/img-peliculas', $nombre_imagen);
            $pelicula->imagen = "storage/img-peliculas/" . $nombre_imagen;
        }

        if ($pelicula->save()) {
            return response()->json(["mensaje" => "Registro exitoso", "datos" => $pelicula], 201);
        } else {
            return response()->json(["mensaje" => "No se pudo realizar el registro"], 422);
        }
    }

    public function update(Request $request, string $id)
    {
        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
        return response()->json([
            "mensaje" => "La película con ID $id no fue encontrada."
        ], 404);
        }
        $request->validate([
            "nombre" => "required",
            "descripcion" => "required",
            "precio" => "required|numeric|min:0",
            "imagen" => "mimes:png,jpg,jpeg|max:2048",
            "categoria" => "required|exists:categorias,id",
        ], [
            "nombre.required" => "El título es requerido para la edición del regstro",
            "descripcion.required" => "La descripción es requerida para la edición del regstro",
            "precio.required"  => "El precio es requerido para la edición del regstro",
            "precio.numeric" => "El precio debe ser un número válido",
            "precio.min" => "El precio no puede ser negativo",
            "imagen.required" => "La imagen es requerida para la edición del regstro",
            "imagen.mimes" => "Formato de la imagen no permitido, formatos permitidos: PNG, JPG y JPEG",
            "imagen.max" => "El tamaño de la imagen no debe superar los 2MB",
            "categoria.required" => "La categoría es requerida para la edición del regstro",
            "categoria.exists" => "La categoría seleccionada no es válida"
        ]);
        $pelicula->nombre = $request->nombre;
        $pelicula->descripcion = $request->descripcion;
        $pelicula->precio = $request->precio;
        $pelicula->categoria_id = $request->categoria;

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($pelicula->imagen && file_exists(public_path($pelicula->imagen))) {
                unlink(public_path($pelicula->imagen));
            }

            // Guardar la nueva imagen
            $file = $request->file('imagen');
            $nombre_imagen = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/img-peliculas', $nombre_imagen);
            $pelicula->imagen = "storage/img-peliculas/" . $nombre_imagen;
        }

        if ($pelicula->save()) {
            return response()->json(["mensaje" => "Edición exitosa", "datos" => $pelicula], 201);
        } else {
            return response()->json(["mensaje" => "No se pudo realizar la edición del registro"], 422);
        }
    }
    public function show(string $id)
    {
        $pelicula = Pelicula::where('id',$id)->first();
        return response()->json(["mensaje"=>"Registro cargado","datos"=>$pelicula]);
    }

    public function destroy(string $id)
    {
        $pelicula= Pelicula::find($id);
        $pelicula->is_deleted = !$pelicula->is_deleted;
        if($pelicula->save()){
            return response()->json(["mensaje"=>"Pelicula eliminada","datos"=>$pelicula],202);
        }
        else{
            return response()->json(["mensaje"=>"No se puede eliminar la película"],422);
        }
    }
}
