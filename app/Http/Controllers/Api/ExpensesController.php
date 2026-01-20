<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Utils\ResponseHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    use ResponseHandler;

    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'sometimes|integer|min:0',
            'page' => 'sometimes|integer|min:1',
            'start_date' => 'sometimes|date|date_format:Y-m-d',
            'end_date' => 'sometimes|date|date_format:Y-m-d',
        ]);

        $perPage = $request->per_page ?? 25;
        $q = $request->q;
        $userId = $request->user()->id;
        $categoryId = $request->category_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $data = Expense::with('category:id,name')
            ->where('user_id', $userId);

        if ($q) {
            $data->where('description', 'like', '%' . $q . '%');
        }

        if ($categoryId) {
            $data->where('category_id', $categoryId);
        }

        if ($startDate) {
            $data->where('expense_date', '>=', $startDate);
        }

        if ($endDate) {
            $data->where('expense_date', '<=', $endDate);
        }

        $data->orderByDesc('created_at');
        $data = $data->paginate($perPage);
        return $this->createResponse($data, 200, 'Success');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'expense_date' => 'sometimes|required|date_format:Y-m-d',
        ]);

        $input = $request->except('user_id', 'expense_date');
        $input['user_id'] = $request->user()->id;
        $input['expense_date'] = $request->expense_date ?? Carbon::today();

        $data = Expense::create($input);
        return $this->createResponse($data, 200, 'Saved successfully');
    }

    public function show(Request $request, string $id)
    {
        $data = Expense::with('category:id,name')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);
        return $this->createResponse($data, 200, 'Success');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'expense_date' => 'sometimes|required|date_format:Y-m-d',
        ]);

        $data = Expense::where('user_id', $request->user()->id)->findOrFail($id);
        $input = $request->except('user_id');

        $data->update($input);
        return $this->createResponse($data, 200, 'Saved successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $data = Expense::where('user_id', $request->user()->id)->findOrFail($id);

        $data->delete();
        return $this->createResponse(null, 200, 'Deleted successfully');
    }
}
