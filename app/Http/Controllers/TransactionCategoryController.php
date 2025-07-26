<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use App\Http\Controllers\Controller;
use App\Http\Repositories\TransactionCategoryRepository;
use App\Http\Requests\TransactionCategory\Create;
use App\Http\Requests\TransactionCategory\Update;
use Illuminate\Http\Request;

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
        $categories = TransactionCategory::latest()->paginate(15);
        return view('transaction-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('transaction-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expense',
            'color' => 'nullable|string|max:7',
        ]);

        $category = TransactionCategory::create($validated);

        return redirect()->route('transaction-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(TransactionCategory $transactionCategory)
    {
        return view('transaction-categories.show', compact('transactionCategory'));
    }

    public function edit(TransactionCategory $transactionCategory)
    {
        return view('transaction-categories.edit', compact('transactionCategory'));
    }

    public function update(Request $request, TransactionCategory $transactionCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:income,expense',
            'color' => 'nullable|string|max:7',
        ]);

        $transactionCategory->update($validated);

        return redirect()->route('transaction-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        $transactionCategory->delete();

        return redirect()->route('transaction-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    // API methods (keeping for backward compatibility)
    public function apiIndex()
    {
        return $this->transactionCategoryRepository->all();
    }

    public function apiShow($id)
    {
        $transactionCategory = $this->transactionCategoryRepository->get($id);
        return success($transactionCategory, 'Get Transaction Category Successfully');
    }

    public function apiStore(Create $request)
    {
        $transactionCategory = $this->transactionCategoryRepository->create($request->all());
        return success($transactionCategory, 'Transaction Category Created Successfully', 201);
    }

    public function apiUpdate(TransactionCategory $transactionCategory, Update $request)
    {
        $transactionCategory = $this->transactionCategoryRepository->update($transactionCategory, $request->all());
        return success($transactionCategory, 'Transaction Category Updated Successfully');
    }

    public function apiDestroy(TransactionCategory $transactionCategory)
    {
        $this->transactionCategoryRepository->delete($transactionCategory);
        return success(null, 'Transaction Category Deleted Successfully', 204);
    }
}
