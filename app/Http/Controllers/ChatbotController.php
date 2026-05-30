<?php
namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbotService) {}

    public function index()
    {
        $history = ChatHistory::where('user_id', auth()->id())
            ->orderBy('created_at')
            ->get();
        return view('chatbot.index', compact('history'));
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $reply = $this->chatbotService->ask(auth()->user(), $request->message);

        return response()->json(['reply' => $reply]);
    }

    public function clearHistory()
    {
        ChatHistory::where('user_id', auth()->id())->delete();
        return response()->json(['status' => 'cleared']);
    }
}
