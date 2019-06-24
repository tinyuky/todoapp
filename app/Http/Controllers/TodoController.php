<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Validator;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class TodoController extends Controller
{
    public function getall(Request $request, $paginate = 10){
        $search = $request['search'];
        if($search != ''){
            $todos =  Todo::orderBy('created_at')
                        ->where('name','like','%'.$search.'%')
                            ->paginate($paginate);
        }
        else{
            $todos = Todo::orderBy('created_at')
                ->paginate($paginate);
        }
        return response()->json($todos);
    }

    public function get($id){
        $todo = Todo::find($id);
        return response()->json(['code' => 1, 'data' => $todo]);
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name'          => 'required', 
            'status'           => 'required|boolean',
            'description'   => 'required',
            'start_time'      => 'required',
            'end_time'      => 'required',
            'priority'        => "required|in:1,2,3,4"
        ]);
        $errors = $validator->errors()->all();
        if (count($errors) > 0) {
            return response()->json([
                'code' => 0,
                'msg' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $add = new Todo;
            $add->name = $request['name'];
            $add->status = $request['status'];
            $add->description = $request['description'];
            $add->start_time = $request['start_time'];
            $add->end_time = $request['end_time'];
            $add->priority = $request['priority'];
            $add->save();

            DB::commit();
            return response()->json([
                'code' => 1,
                'msg' => "Add success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 0,
                'msg' => $e
            ]);
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' =>'required|numeric',
            'name'          => 'required', 
            'status'           => 'required|boolean',
            'description'   => 'required',
            'start_time'      => 'required',
            'end_time'      => 'required',
            'priority'        => "required|in:1,2,3,4"
        ]);
        $errors = $validator->errors()->all();
        if (count($errors) > 0) {
            return response()->json([
                'code' => 0,
                'msg' => $errors
            ]);
        }
        $add = Todo::find($request['id']);
        if(!$add){
            return response()->json([
                'code' => 0,
                'msg' => "Can't find element"
            ]);
        }

        DB::beginTransaction();
        try {
            $add->name = $request['name'];
            $add->status = $request['status'];
            $add->description = $request['description'];
            $add->start_time = $request['start_time'];
            $add->end_time = $request['end_time'];
            $add->priority = $request['priority'];
            $add->save();

            DB::commit();
            return response()->json([
                'code' => 1,
                'msg' => "Update success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 0,
                'msg' => $e
            ]);
        }
    }

    public function delete($id){
        DB::beginTransaction();
        try {
            Todo::find($id)->delete();

            DB::commit();
            return response()->json([
                'code' => 1,
                'msg' => "Delete success"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 0,
                'msg' => $e
            ]);
        }
    }
}
