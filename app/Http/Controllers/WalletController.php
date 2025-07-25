<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Controllers\Controller;
use App\Http\Repositories\WalletRepository;
use App\Http\Requests\Wallet\Create;
use App\Http\Requests\Wallet\Update;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletRepository $walletRepository
    )
    {
        $this->authorizeApiResource("wallets");
    }

    public function index()
    {
        return $this->walletRepository->all();
    }

    public function show($id)
    {
        $wallet = $this->walletRepository->get($id);
        return success($wallet, 'Get Wallet Successfully');
    }

    public function store(Create $request)
    {
        $wallet = $this->walletRepository->create($request->all());
        return success($wallet, 'Wallet Created Successfully', 201);
    }

    public function update(Wallet $wallet, Update $request)
    {
        $wallet = $this->walletRepository->update($wallet, $request->all());
        return success($wallet, 'Wallet Updated Successfully');
    }

    public function destroy(Wallet $wallet)
    {
        $this->walletRepository->delete($wallet);
        return success(null, 'Wallet Deleted Successfully', 204);
    }
}
