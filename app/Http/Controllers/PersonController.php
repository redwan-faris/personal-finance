<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PersonRepository;
use App\Http\Requests\Person\Create;
use App\Http\Requests\Person\Update;

class PersonController extends Controller
{
    public function __construct(
        private readonly PersonRepository $personRepository
    )
    {
        $this->authorizeApiResource("people");
    }

    public function index()
    {
        return $this->personRepository->all();
    }

    public function show($id)
    {
        $person = $this->personRepository->get($id);
        return success($person, 'Get Person Successfully');
    }

    public function store(Create $request)
    {
        $person = $this->personRepository->create($request->all());
        return success($person, 'Person Created Successfully', 201);
    }

    public function update(Person $person, Update $request)
    {
        $person = $this->personRepository->update($person, $request->all());
        return success($person, 'Person Updated Successfully');
    }

    public function destroy(Person $person)
    {
        $this->personRepository->delete($person);
        return success(null, 'Person Deleted Successfully', 204);
    }
}
