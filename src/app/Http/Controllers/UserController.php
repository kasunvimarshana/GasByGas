<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService\UserService;
use App\Models\User;

class UserController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected UserService $userService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        UserService $userService
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        // $rules = [];
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            $userQueryBuilder = $this->userService->query();
            $userQueryBuilder = User::query();

            $users = $this->paginationService->paginate($userQueryBuilder,
                                                        $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $users, null);
            }

            return view('pages.users.index', compact('users'));
        } catch (Exception $e) {
            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexForCompany(Request $request) {
        // $rules = [];
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            $userQueryBuilder = $this->userService->query();
            $userQueryBuilder = User::query();
            $userQueryBuilder = $userQueryBuilder->whereHas('company', function($q) {
                $companyId = optional(auth()->user()?->company)->id;
                // $q->where('companies.id', $companyId);
                $q->where('company_users.company_id', $companyId);
            });

            $users = $this->paginationService->paginate($userQueryBuilder,
                                                        $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $users, null);
            }

            return view('pages.users.index', compact('users'));
        } catch (Exception $e) {
            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    public function create() {
        return view('pages.users.create');
    }

    public function createForCompany() {
        return view('pages.users.create-for-company');
    }

    public function store(StoreUserRequest $request) {
        // $rules = $request->rules(); // []
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            DB::beginTransaction(); // Main transaction

            // Create user from User module
            $user = $this->userService->create($request->all());

            $userRole = config('roles_and_permissions.roles.user');
            $user->assignRole($userRole);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'users.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    public function storeForCompany(StoreUserRequest $request) {
        // $rules = $request->rules(); // []
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            DB::beginTransaction(); // Main transaction

            // Create user from User module
            $user = $this->userService->create($request->all());

            auth()->user()->company->users()->attach($user->id);

            $adminRole = config('roles_and_permissions.roles.admin');
            $user->assignRole($adminRole);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'users.index-for-company',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    public function edit(User $user) {
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user) {
        // $rules = $request->rules(); // []
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            DB::beginTransaction(); // Main transaction

            // Create user from User module
            $user = $this->userService->update($user->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'users.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    public function destroy(User $user) {
        // $rules = $request->rules(); // []
        // $validated = $request->validate($rules);
        // $validator = Validator::make($request->all(), $rules);
        // if( $validator->fails() ) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        try {
            DB::beginTransaction(); // Main transaction

            // Create user from User module
            $this->userService->delete($user->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'users.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            $friendlyMessage = $e->getMessage() ?? trans('messages.general_error', []);
            return $this->handleException($e, $friendlyMessage);
        }
    }

    public function show(User $user) {
        return view('pages.users.show', compact('user'));
    }
}
