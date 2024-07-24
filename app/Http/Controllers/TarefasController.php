<?php

namespace App\Http\Controllers;

use App\Models\Tarefas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefasController extends Controller
{

    public function createTarefa(Request $request) {

        $userAuth = Auth::user();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'string|max:1000',
        ]);

        $tarefa = $request->only(['titulo', 'descricao']);
        $tarefa['user_id'] = $userAuth->id;
        //dd($tarefa);
        $dbTarefa = Tarefas::create($tarefa);

        return response()->json([
            'mensagem' => 'Recurso criado com sucesso',
            'data' => $dbTarefa,
        ], 201);
    }

    public function tarefasUsuario() {

        $userAuth = Auth::user();

        $tasks = Tarefas::where('user_id', '=', $userAuth->id)
                        ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json([
            'mensagem' => $tasks->count() > 0 ? 'Recurso Encontrada' : 'Nao existe recurso para esss usuario',
            'data' => $tasks,
        ], 200);
    }

    public function updateTarefaUser(Request $request, $id) {

        //dd($request->all());
        $userAuth = Auth::user();

        $request_data = $request->only(['titulo', 'descricao', 'status']);

        $tarefa = Tarefas::where('id', '=', $id)
                    ->where('user_id', '=', $userAuth->id)
                        ->first();

        if(!$tarefa) {
            return response()->json([
                'mensagem' => 'Nao possivel atualizar esse recurso ' .$id,
            ], 400);
        }

        //  dd($tarefa);

        $tarefa->update($request_data);
        $tarefa->save();

        return response()->json([
            'mensagem' => 'Atualizacao com sucesso',
            'data' => $tarefa
        ], 201);

    }


}
