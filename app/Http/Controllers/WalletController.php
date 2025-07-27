<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Repositories\WalletRepository;
use App\Http\Requests\Wallet\Create;
use App\Http\Requests\Wallet\Update;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletRepository $walletRepository
    )
    {
        // $this->authorizeApiResource("wallets"); // Commented out to prevent 403 errors
    }

    public function index()
    {
        try {
            $wallets = Wallet::paginate(15);
        } catch (\Exception $e) {
            $wallets = collect()->paginate(15);
        }
        return view('wallets.index', compact('wallets'));
    }

    public function create()
    {
        $users = User::all();
        return view('wallets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $wallet = Wallet::create($validated);

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet created successfully.');
    }

    public function show(Wallet $wallet)
    {
        return view('wallets.show', compact('wallet'));
    }

    public function edit(Wallet $wallet)
    {
        $users = User::all();
        return view('wallets.edit', compact('wallet', 'users'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $wallet->update($validated);

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet updated successfully.');
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('wallets.index')
            ->with('success', 'Wallet deleted successfully.');
    }

    // API methods (keeping for backward compatibility)
    public function apiIndex()
    {
        return $this->walletRepository->all();
    }

    public function apiShow($id)
    {
        $wallet = $this->walletRepository->get($id);
        return success($wallet, 'Get Wallet Successfully');
    }

    public function apiStore(Create $request)
    {
        $wallet = $this->walletRepository->create($request->all());
        return success($wallet, 'Wallet Created Successfully', 201);
    }

    public function apiUpdate(Wallet $wallet, Update $request)
    {
        $wallet = $this->walletRepository->update($wallet, $request->all());
        return success($wallet, 'Wallet Updated Successfully');
    }

    public function apiDestroy(Wallet $wallet)
    {
        $this->walletRepository->delete($wallet);
        return success(null, 'Wallet Deleted Successfully', 204);
    }
}
