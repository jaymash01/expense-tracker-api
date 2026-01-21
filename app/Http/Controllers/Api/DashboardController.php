<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use App\Utils\ResponseHandler;
use Carbon\Carbon;
use Exception;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class DashboardController extends Controller
{
    use ResponseHandler;

    public function __invoke(Request $request)
    {

        $user = $request->user();

        $data = [
            'summary' => [
                'this_month_expenses' => 0,
                'last_month_expenses' => 0,
                'ai_insights' => null,
            ],
            'lists' => [
                'recent_expenses' => [],
            ]
        ];

        $now = Carbon::now();

        $data['summary']['this_month_expenses'] = Expense::where('user_id', $user->id)
            ->where('expense_date', '>=', $now->clone()->startOfMonth())
            ->sum('amount');

        $data['summary']['last_month_expenses'] = Expense::where('user_id', $user->id)
            ->where('expense_date', '>=', $now->clone()->subMonthNoOverflow()->startOfMonth())
            ->where('expense_date', '<=', $now->clone()->subMonthNoOverflow()->endOfMonth())
            ->sum('amount');

        $data['summary']['ai_insights'] = $this->getSummaryInsights($user);

        $data['lists']['recent_expenses'] = Expense::with('category:id,name')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return $this->createResponse($data, 200, 'Success');
    }

    private function getSummaryInsights(User $user)
    {
        $aiModel = env('AI_MODEL', 'open-ai');

        try {
            $expenses = $user->expenses()
                ->with('category')
                ->where('expense_date', '>=', Carbon::now()->startOfMonth())
                ->get()
                ->groupBy('category.name')
                ->map(fn($group) => $group->sum('amount'));

            $totalSpent = $expenses->sum();
            $summary = "Total spent this month: {$totalSpent}. Breakdown: " . $expenses->toJson();

            // Cache the insights for 24 hours so we don't re-run the API call every time the API is called
            return Cache::remember("user_insights_{$user->id}", 86400, function () use ($aiModel, $summary) {
                return $aiModel == 'open-ai' ? $this->askOpenAI($summary) : $this->askGeminiAI($summary);
            });
        } catch (Exception $e) {
            Log::debug($e);
            return null;
        }
    }

    private function askOpenAI($summary)
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful financial advisor. Analyze the user\'s monthly spending and provide 3 concise bullet points for improvement. The currency used is Tanzanian Shilling. Be encouraging.'
                ],
                [
                    'role' => 'user',
                    'content' => "Here is my spending data for this month: {$summary}"
                ],
            ],
        ]);

        return $result->choices[0]->message->content;
    }

    private function askGeminiAI($summary)
    {
        $result = Gemini::geminiPro()
            ->withSystemInstruction(Content::parse('You are a helpful financial advisor. Analyze the user\'s monthly spending and provide 3 concise bullet points for improvement. The currency used is Tanzanian Shilling. Be encouraging.'))
            ->generateContent("Here is my spending data for this month: {$summary}");

        return $result->text();
    }
}
