<?php

namespace App\Http\Controllers;

use App\Http\Requests\TarefaRequest;
use App\Models\Tarefas;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TarefasController extends Controller
{

    private $userAuth;

    public function __construct() {
        $this->userAuth = Auth::user();
    }

    public function createTarefa(TarefaRequest $request) {

        try {

            DB::beginTransaction();


            $tarefa = $request->only(['titulo', 'descricao']);
            $tarefa['user_id'] = $this->userAuth->id;

            $dbTarefa = Tarefas::create($tarefa);

            DB::commit();

            return response()->json([
                'mensagem' => 'Recurso criado com sucesso',
                'data' => $dbTarefa,
            ], 201);


        } catch(Exception $e) {

            DB::rollBack();

            return response()->json([
                'mensagem' => $e->getMessage(),
            ], 500);
        }
    }

    public function tarefasUsuario() {
        try {

            $tasks = Tarefas::where('user_id', '=', $this->userAuth->id)
                            ->orderBy('created_at', 'desc')
                                ->get();

            return response()->json([
                'data' => $tasks,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTarefaUser(TarefaRequest $request, $id) {

        try {

            DB::beginTransaction();

            $request_data = $request->only(['titulo', 'descricao', 'status']);

            $tarefa = $this->getTarefa($id, $this->userAuth->id);

            if(!$tarefa) {
                return response()->json([
                    'mensagem' => 'Nao possivel atualizar esse recurso ' .$id,
                ], 400);
            }

            //  dd($tarefa);

            $tarefa->update($request_data);
            $tarefa->save();

            DB::commit();

            return response()->json([
                'mensagem' => 'Atualizacao com sucesso',
                'data' => $tarefa
            ], 201);

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'mensagem' => $e->getMessage()

            ], 500);
        }

    }

    public function deleteTarefaUser($id) {

        try {

            DB::beginTransaction();

            $tarefa = $this->getTarefa($id, $this->userAuth->id);

            if(!$tarefa) {
                return response()->json([
                    'mensagem' => 'Nao possivel deletar esse recurso ' .$id,
                ], 400);
            }

            $tarefa->delete();

            DB::commit();

            return response()->json([
                'mensagem' => 'Recurso deletado com sucesso',
                'data' => $tarefa
            ], 200);

        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'mensagem' => $e->getMessage()
            ], 500);

        }

    }

    private function getTarefa($id, $auth_id) {

        $tarefa = Tarefas::where('id', '=', $id)
                    ->where('user_id', '=', $auth_id)
                        ->first();

        return $tarefa;
    }

}
