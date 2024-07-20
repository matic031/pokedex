<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;

class PokemonController extends Controller
{

    public function index()
    {
        $pokemons = Pokemon::all();
        return response()->json($pokemons);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|regex:/^[A-Za-z ]+$/',
            'description' => 'required|string|max:300',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images', 'public');
            $imageUrl = Storage::url($path);

            $pokemon = Pokemon::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imageUrl
            ]);

            return response()->json($pokemon, 201);
        }

        return response()->json(['error' => 'Image upload failed'], 400);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|regex:/^[A-Za-z ]+$/',
            'description' => 'required|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024'
        ]);

        $pokemon = Pokemon::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images', 'public');
            $imageUrl = Storage::url($path);

            // delete old image
            if ($pokemon->image) {
                $oldPath = str_replace('/storage', 'public', $pokemon->image);
                Storage::delete($oldPath);
            }

            $pokemon->image = $imageUrl;
        }

        $pokemon->name = $request->name;
        $pokemon->description = $request->description;
        $pokemon->save();

        return response()->json($pokemon, 200);
    }

    public function destroy($id)
    {
        $pokemon = Pokemon::findOrFail($id);

        // delete image froms torage
        if ($pokemon->image) {
            $oldPath = str_replace('/storage', 'public', $pokemon->image);
            Storage::delete($oldPath);
        }

        $pokemon->delete();

        return response()->json(['message' => 'Pokemon deleted successfully'], 200);
    }
}
