<?php

namespace App\Http\Controllers;


use App\Models\Folder;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(int $id,Folder $folder)
    {
        // ★ ユーザーのフォルダを取得する
        $folders = Auth::user()->folders()->get();
       
        // すべてのフォルダを取得する
    // $folders = Folder::all();

    // 選ばれたフォルダを取得する
    $current_folder = Folder::find($id);

   


    // 選ばれたフォルダに紐づくタスクを取得する
    $tasks = $current_folder->tasks()->get(); // ★

    return view('tasks/index', [
        'folders' => $folders,
        'current_folder' => $current_folder,
        'current_folder_id' => $current_folder->id,
        'tasks' => $tasks,
    ]);
} 

/**
 * GET /folders/{id}/tasks/create
 */
public function showCreateForm(int $id)
{
    return view('tasks/create', [
        'folder_id' => $id
    ]);
}

/**
     * タスク作成
     * @param Int $id
     * @param CreateTask $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(int $id, CreateTask $request)
    {

    $current_folder = Folder::find($id);

    $task = new Task();
    $task->title = $request->title;
    $task->due_date = $request->due_date;

    $current_folder->tasks()->save($task);

    return redirect()->route('tasks.index', [
        'id' => $current_folder->id,
    ]);
    }

    /**
 * GET /folders/{id}/tasks/{task_id}/edit
 */
public function showEditForm(int $id, int $task_id)
{
    $task = Task::find($task_id);

    return view('tasks/edit', [
        'task' => $task,
    ]);
}

public function edit(int $id, int $task_id, EditTask $request)
{
    // 1
    $task = Task::find($task_id);

    // 2
    $task->title = $request->title;
    $task->status = $request->status;
    $task->due_date = $request->due_date;
    $task->save();

    // 3
    return redirect()->route('tasks.index', [
        'id' => $task->folder_id,
    ]);
}
}


//     /**
//      * タスク編集フォーム
//      * @param Folder $folder
//      * @param Task $task
//      * @return \Illuminate\View\View
//      */
//     public function showEditForm(Folder $folder, Task $task)
//     {
//         $this->checkRelation($folder, $task);

//         // $task = Task::find($task_id);

//         return view('tasks/edit', [
//             'task' => $task,
//         ]);
//     }


//     /**
//      * タスク編集
//      * @param Folder $folder
//      * @param Task $task
//      * @param EditTask $request
//      * @return \Illuminate\Http\RedirectResponse
//      */
//     public function edit(Folder $folder, Task $task, EditTask $request)
//     {

//         $this->checkRelation($folder, $task);

//         $task->title = $request->title;
//         $task->status = $request->status;
//         $task->due_date = $request->due_date;
//         $task->save();

//         return redirect()->route('tasks.index', [
//             'id' => $task->folder_id,
//         ]);
//     }
//     private function checkRelation(Folder $folder, Task $task)
//     {
//         // if ($folder->id !== $task->folder_id) {
//         //     abort(404);
//         // }
//     }

//     /**
//   * 状態が定義された値ではない場合はバリデーションエラー
//   * @test
//   */
// public function status_should_be_within_defined_numbers()
// {
//     $this->seed('TasksTableSeeder');

//     $response = $this->post('/folders/1/tasks/1/edit', [
//         'title' => 'Sample task',
//         'due_date' => Carbon::today()->format('Y/m/d'),
//         'status' => 999,
//     ]);

//     $response->assertSessionHasErrors([
//         'status' => '状態 には 未着手、着手中、完了 のいずれかを指定してください。',
//     ]);
// }

// }

// // 
