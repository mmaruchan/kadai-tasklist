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
        
            return view('welcome');
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
        if (\Auth::id() === $task->user_id){
        return view('tasks.show', [
            'task' => $task,
        ]);
        }
        
        return redirect('/');
        
    }

    // tasks/editにアクセスされたときの処理
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::find($id);
        
        // メッセージ編集ビューでそれを表示
        if (\Auth::id() === $task->user_id){
        return view('tasks.edit', [
            'task' => $task,
        ]);
        }
        
        return redirect('/');
        
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
        if (\Auth::id() === $task->user_id){
        $task->user_id = $request->user()->id;
        // たすくを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    //
    public function destroy($id)
    {
        // idの値でたすくを検索して取得
        $task = Task::find($id);
        // タスクを削除
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();    
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}