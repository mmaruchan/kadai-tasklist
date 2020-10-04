<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { //認証済みの場合
            //認証済みユーザを取得
            $user = \Auth::user();
            //ユーザの投稿一覧を昇順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'asc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];

            return view('tasks.index', [
                'tasks' => $tasks,
            ]);

        } 
        
        else {
            return view('welcome');    
        }

    }

    // tasks/createにアクセスされときの処理
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // tasks/storeにアクセスされときの処理
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);

        // たすくを作成
        $task = new Task;
        $task->user_id = $request->user()->id;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // tasks/showにアクセスされたときの処理
    public function show($id)
    {
        // idの値でたすくを検索して取得
        $task = Task::find($id);

        // ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    // tasks/editにアクセスされたときの処理
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::find($id);
        
        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    //
    public function update(Request $request, $id)
    {

        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);

        // idの値でたすくを検索して取得
        $task = Task::find($id);
        $task->user_id = $request->user()->id;
        // たすくを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    //
    public function destroy($id)
    {
        // idの値でたすくを検索して取得
        $task = Task::find($id);
        // タスクを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}