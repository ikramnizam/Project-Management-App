<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();  //$user is a variable that holds the authenticated user object, which includes all user-related data like id, name, email, etc  //retrieves current auth user //"user()" method return user that is currently logged in
        $totalPendingTasks = Task::query()    //display total number of pending task
            ->where('status', 'pending')      // 1st condition: filter status column  is equal to pending
            ->count();                        // 2nd condition: return all task yg ada status = pending
        $myPendingTasks = Task::query()      //display pending task
            ->where('status', 'pending')
            ->where('assigned_user_id', $user->id)  //filter assgined user id column yg match dgn ID current authenticated user
            ->count();


        $totalProgressTasks = Task::query()
            ->where('status', 'in_progress')
            ->count();
        $myProgressTasks = Task::query()
            ->where('status', 'in_progress')
            ->where('assigned_user_id', $user->id)
            ->count();


        $totalCompletedTasks = Task::query()
            ->where('status', 'completed')
            ->count();
        $myCompletedTasks = Task::query()
            ->where('status', 'completed')
            ->where('assigned_user_id', $user->id)
            ->count();

        $activeTasks = Task::query()
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('assigned_user_id', $user->id)
            ->limit(10)
            ->get();
        $activeTasks = TaskResource::collection($activeTasks);
        return inertia(
            'Dashboard',
            compact(
                'totalPendingTasks',
                'myPendingTasks',
                'totalProgressTasks',
                'myProgressTasks',
                'totalCompletedTasks',
                'myCompletedTasks',
                'activeTasks'
            )
        );
    }
}