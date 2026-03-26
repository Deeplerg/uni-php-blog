<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;

class BugReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'error_code' => 'required|integer',
            'url' => 'required|string',
        ]);

        BugReport::create([
            'user_id' => auth()->id(),
            'error_code' => $request->error_code,
            'url' => $request->url,
            'message' => $request->message,
        ]);

        //return redirect()->route('posts.index')->with('status', 'Bug report sent. Thank you!');
        return redirect()->route('posts.index');
    }
}
