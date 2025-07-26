@extends('layouts.app')

@section('title', 'Wallets')

@section('content')
<div class="space-y-6">
    <!-- Page header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Wallets</h1>
            <p class="mt-2 text-sm text-gray-700">Manage all your financial wallets.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('wallets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Wallet
            </a>
        </div>
    </div>

    <!-- Wallets grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($wallets as $wallet)
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $wallet->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $wallet->user->name ?? 'Unknown User' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-900">
                            {{ number_format($wallet->balance ?? 0, 2) }}
                        </span>
                        <span class="text-sm text-gray-500">Balance</span>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex space-x-2">
                        <a href="{{ route('wallets.show', $wallet) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                        <a href="{{ route('wallets.edit', $wallet) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">Edit</a>
                    </div>
                    <form action="{{ route('wallets.destroy', $wallet) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No wallets</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new wallet.</p>
                <div class="mt-6">
                    <a href="{{ route('wallets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Wallet
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if($wallets->hasPages())
        <div class="mt-6">
            {{ $wallets->links() }}
        </div>
    @endif
</div>
@endsection
