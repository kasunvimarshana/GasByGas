<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use App\Services\NotificationService\NotificationServiceInterface;
use App\Services\PaginationService\PaginationServiceInterface;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Services\CompanyService\CompanyService;
use App\Models\Company;
use Exception;

class CompanyController extends BaseController {
    protected NotificationServiceInterface $notificationService;
    protected PaginationServiceInterface $paginationService;
    protected CompanyService $companyService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PaginationServiceInterface $paginationService,
        CompanyService $companyService
    ) {
        parent::__construct($notificationService);

        $this->notificationService = $notificationService;
        $this->paginationService = $paginationService;
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            $companyQueryBuilder = $this->companyService->query();

            $companyQueryBuilder = $companyQueryBuilder->where(function($q) {
                $companyId = optional(auth()->user()?->company)->id;
                $q->where('id', $companyId);
            });

            $companies = $this->paginationService->paginate($companyQueryBuilder,
                                                            $request->perPage ?? 15);

            // Handle JSON response if requested
            if ($request->expectsJson()) {
                return $this->formatJsonResponse(true, '', $companies, null);
            }

            return view('pages.companies.index', compact('companies'));
        } catch (Exception $e) {
            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('pages.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request) {
        try {
            DB::beginTransaction(); // Main transaction

            $company = $this->companyService->create($request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'companies.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) {
        return view('pages.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company) {
        return view('pages.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company) {
        try {
            DB::beginTransaction(); // Main transaction

            $company = $this->companyService->update($company->id, $request->all());

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'companies.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company) {
        try {
            DB::beginTransaction(); // Main transaction

            $this->companyService->delete($company->id);

            DB::commit(); // Commit transaction if all succeeds

            return $this->handleResponse(
                trans('messages.thank_you', []),
                null,
                'companies.index',
                [],
            );
        } catch (Exception $e) {
            DB::rollBack(); // Rollback if any operation fails

            return $this->handleException($e, trans('messages.general_error', []));
        }
    }
}
