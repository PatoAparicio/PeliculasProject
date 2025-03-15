<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();

        return response()->json([
            "mensaje" => $categorias->isEmpty() ? "No existen categorías registradas" : "Categorías cargadas",
            "datos" => $categorias
        ], 200);
    }

    public function indexDisponibles()
    {
        $categorias = Categoria::where('is_deleted', false)->get();

        return response()->json([
            "mensaje" => $categorias->isEmpty() ? "No hay categorías disponibles" : "Categorías disponibles cargadas",
            "datos" => $categorias
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required|unique:categorias,nombre"
        ], [
            "nombre.required" => "El Nombre es requerido para registrar la categoría",
            "nombre.unique" => "El Nombre ya está registrado"
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;

        if ($categoria->save()) {
            return response()->json(["mensaje" => "Registro exitoso", "datos" => $categoria], 201);
        } else {
            return response()->json(["mensaje" => "No se pudo realizar el registro"], 422);
        }
    }

    public function show($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(["mensaje" => "Categoría no encontrada"], 404);
        }

        return response()->json(["mensaje" => "Registro cargado", "datos" => $categoria]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "nombre" => "required|unique:categorias,nombre," . $id
        ], [
            "nombre.required" => "El Nombre es requerido para editar la categoría",
            "nombre.unique" => "El Nombre ya está registrado"
        ]);

        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(["mensaje" => "Categoría no encontrada"], 404);
        }

        $categoria->nombre = $request->nombre;

        if ($categoria->save()) {
            return response()->json(["mensaje" => "Edición exitosa", "datos" => $categoria], 200);
        } else {
            return response()->json(["mensaje" => "No se pudo realizar la edición"], 422);
        }
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(["mensaje" => "Categoría no encontrada"], 404);
        }

        $categoria->is_deleted = !$categoria->is_deleted;

        if ($categoria->save()) {
            $mensaje = $categoria->is_deleted ? "Categoría deshabilitada" : "Categoría habilitada";
            return response()->json(["mensaje" => $mensaje, "datos" => $categoria], 200);
        } else {
            return response()->json(["mensaje" => "No se pudo cambiar el estado de la categoría"], 422);
        }
    }
}
