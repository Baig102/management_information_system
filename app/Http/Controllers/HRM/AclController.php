<?php

namespace App\Http\Controllers\HRM;

use Exception;
use App\Models\User;
use App\Models\UserMenu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use App\Models\Module;
use App\Models\roleAcl;
use Illuminate\Support\Facades\Route;

class AclController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo "ACL LIST";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', 1)->get();
        // dd($users);
        return view('modules.HRM.acl.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        echo "ACL SHOW";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        echo "ACL EDIT";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        echo "ACL UPDATE";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        echo "ACL DELETE";
    }

    public function getModulesByUser($userId)
    {
        // If the user doesn't exist, return an error
        return response()->json(['message' => 'User not found.'], 404);
    }

    /**
     * Get routes based on the selected module
     */
    public function getRoutesByModule($moduleId, $user_id = null, $role_id = null)
    {
        // return response()->json([$moduleId, $user_id, $role_id]);
        // Define the route groups based on modules.
        // Assuming that each module has a prefix for its routes in `web.php`

        $moduleRoutes = [];
        $user_menu = [];

        return array("routes"=>$moduleRoutes, "selected_menu"=>$user_menu);

        // return response()->json(['data' => $moduleRoutes]);
    }

    /**
     * Get all routes based on the prefix
     */
    private function getRoutesByPrefix($prefix)
    {
        // return $user_id;
        // Retrieve all routes in the system
        $moduleRoutes = [];
        return $moduleRoutes;

    }
}
