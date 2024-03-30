<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::all();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $todo = new Todo;
        $todo->text = $request->input('text');
        $todo->save();

        return response()->json($todo);
    }
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->text = $request->input('text');
            $todo->save();
            return response()->json($todo);
        } else {
            return response()->json(['message' => 'Todo not found'], 404);
        }
    }

    public function destroy(Request $request, $id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->delete();
            return response()->json(['message' => 'Todo deleted successfully']);
        } else {
            return response()->json(['message' => 'Todo not found'], 404);
        }
    }
}