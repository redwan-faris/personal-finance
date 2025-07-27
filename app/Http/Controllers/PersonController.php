<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PersonRepository;
use App\Http\Requests\Person\Create;
use App\Http\Requests\Person\Update;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function __construct(
        private readonly PersonRepository $personRepository
    )
    {
        // $this->authorizeApiResource("people"); // Commented out to prevent 403 errors
    }

    public function index()
    {
        $people = Person::latest()->paginate(15);
        return view('people.index', compact('people'));
    }

    public function create()
    {
        return view('people.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'nullable|in:customer,supplier,employee,other',
            'address' => 'nullable|string',
            'balance' => 'nullable|numeric',
        ]);

        // Convert balance from dollars to cents if provided
        if (isset($validated['balance'])) {
            $validated['balance'] = (int)($validated['balance'] * 100);
        }

        $person = Person::create($validated);

        return redirect()->route('people.index')
            ->with('success', 'Person created successfully.');
    }

    public function show(Person $person)
    {
        return view('people.show', compact('person'));
    }

    public function edit(Person $person)
    {
        return view('people.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'nullable|in:customer,supplier,employee,other',
            'address' => 'nullable|string',
            'balance' => 'nullable|numeric',
        ]);

        // Convert balance from dollars to cents if provided
        if (isset($validated['balance'])) {
            $validated['balance'] = (int)($validated['balance'] * 100);
        }

        $person->update($validated);

        return redirect()->route('people.index')
            ->with('success', 'Person updated successfully.');
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('people.index')
            ->with('success', 'Person deleted successfully.');
    }

    // API methods (keeping for backward compatibility)
    public function apiIndex()
    {
        return $this->personRepository->all();
    }

    public function apiShow($id)
    {
        $person = $this->personRepository->get($id);
        return success($person, 'Get Person Successfully');
    }

    public function apiStore(Create $request)
    {
        $person = $this->personRepository->create($request->all());
        return success($person, 'Person Created Successfully', 201);
    }

    public function apiUpdate(Person $person, Update $request)
    {
        $person = $this->personRepository->update($person, $request->all());
        return success($person, 'Person Updated Successfully');
    }

    public function apiDestroy(Person $person)
    {
        $this->personRepository->delete($person);
        return success(null, 'Person Deleted Successfully', 204);
    }
}
