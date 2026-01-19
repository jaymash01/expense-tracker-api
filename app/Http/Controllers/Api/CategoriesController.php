<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Utils\ResponseHandler;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use ResponseHandler;

    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'sometimes|integer|min:0',
            'page' => 'sometimes|integer|min:1',
        ]);

        $perPage = $request->per_page ?? 25;
        $q = $request->q;
        $userId = $request->user()->id;
        $data = Category::where('user_id', $userId);

        if ($q) {
            $data->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%');
            });
        }

        $data->orderBy('name');
        $data = $data->paginate($perPage);
        return $this->createResponse($data, 200, 'Success');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $input = $request->except('user_id');
        $input['user_id'] = $request->user()->id;

        $data = Category::create($input);
        return $this->createResponse($data, 200, 'Saved successfully');
    }

    public function show(Request $request, string $id)
    {
        $data = Category::where('user_id', $request->user()->id)->findOrFail($id);
        return $this->createResponse($data, 200, 'Success');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $data = Category::where('user_id', $request->user()->id)->findOrFail($id);
        $input = $request->except('user_id');

        $data->update($input);
        return $this->createResponse($data, 200, 'Saved successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $data = Category::where('user_id', $request->user()->id)->findOrFail($id);

        $data->delete();
        return $this->createResponse(null, 200, 'Deleted successfully');
    }
}
