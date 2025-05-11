<?php

namespace App\Http\Controllers;

use App\Models\TaskInstance;
use Illuminate\Http\Request;

class TaskInstanceController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:not finished,pending,done',
        ]);

        $instance = TaskInstance::findOrFail($id);
        $instance->status = $request->status;
        if ($instance->status == 'done') {
            $instance->completed_at = now();
        }
        $instance->save();

        return response()->json([
            'success' => true,
            'status' => $instance->status,
            ]);
    }
}
