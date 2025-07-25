<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use App\Http\Controllers\Controller;
use App\Http\Repositories\TransactionCategoryRepository;
use App\Http\Requests\TransactionCategory\Create;
use App\Http\Requests\TransactionCategory\Update;

class TransactionCategoryController extends Controller
{
    public function __construct(
        private readonly TransactionCategoryRepository $transactionCategoryRepository
    )
    {
        $this->authorizeApiResource("transaction_categories");
    }

    public function index()
    {
        return $this->transactionCategoryRepository->all();
    }

    public function show($id)
    {
        $transactionCategory = $this->transactionCategoryRepository->get($id);
        return success($transactionCategory, 'Get Transaction Category Successfully');
    }

    public function store(Create $request)
    {
        $transactionCategory = $this->transactionCategoryRepository->create($request->all());
        return success($transactionCategory, 'Transaction Category Created Successfully', 201);
    }

    public function update(TransactionCategory $transactionCategory, Update $request)
    {
        $transactionCategory = $this->transactionCategoryRepository->update($transactionCategory, $request->all());
        return success($transactionCategory, 'Transaction Category Updated Successfully');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        $this->transactionCategoryRepository->delete($transactionCategory);
        return success(null, 'Transaction Category Deleted Successfully', 204);
    }
}
