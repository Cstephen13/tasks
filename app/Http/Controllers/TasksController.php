<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * @OA\Get(
     *      path="/tasks",
     *      operationId="getProjectsList",
     *      tags={"Tasks"},
     *      summary="Get list of taks",
     *      description="Returns list of taks",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TasksResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     *     )
     */
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Task::with('user')->get();
    }

    /**
     * @OA\Post(
     *      path="/tasks",
     *      operationId="storeTasks",
     *      tags={"Tasks"},
     *      summary="Store new task",
     *      description="Returns task data created",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Tasks")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
           'description' => 'required|string',
           'title' => 'required|string',
           'limit_date' => 'required|date_format:Y-m-d H:i:s'
        ]);

        $taskCreated = Task::create([
            'description' => $fields['description'],
            'title' => $fields['title'],
            'limit_date' => $fields['limit_date'],
            'state' => 0,
            'user_id' =>auth()->id(),
        ]);
        return $taskCreated->load('user');
    }

    /**
     * @OA\Get(
     *      path="/tasks/{id}",
     *      operationId="getTaskById",
     *      tags={"Tasks"},
     *      summary="Get task information",
     *      description="Returns task data",
     *      @OA\Parameter(
     *          id="id",
     *          description="Task id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Tasks")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): \Illuminate\Http\Response
    {
        return Task::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        $task->update($request->all());
        return Task::with('user')->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $task = Task::find($id);
        $task->delete();
        return Task::with('user')->get();
    }
}
