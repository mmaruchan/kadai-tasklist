<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // tasks/にアクセスされたときの表示
    public function index()
    {
        // たすくを取得
        $tasks = Task::all();

        // ビューで表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    // tasks/createにアクセスされときの処理
    public function create()
    {
        $task = new Task;

        // ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // tasks/にアクセスされたときの処理
    public function store(Request $request)
    {
        // たすくを作成
        $task = new Task;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // tasks/idにアクセスされたときの処理
    public function show($id)
    {
        // idの値でたすくを検索して取得
        $task = Task::findOrFail($id);

        // ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    // tasks/id/editにアクセスされたときの処理
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    // messages/idにアクセスされたときの処理
    public function update(Request $request, $id)
    {
        // idの値でたすくを検索して取得
        $task = Task::findOrFail($id);
        // たすくを更新
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // tasks/idにアクセスされたときの削除処理
    public function destroy($id)
    {
        // idの値でたすくを検索して取得
        $task = Task::findOrFail($id);
        // タスクを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
