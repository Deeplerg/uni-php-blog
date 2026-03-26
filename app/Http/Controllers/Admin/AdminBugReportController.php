<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BugReport;

class AdminBugReportController extends Controller
{
    public function index()
    {
        $reports = BugReport::with('user')->latest()->paginate(10);
        return view('admin.bug-reports.index', compact('reports'));
    }

    public function destroy(BugReport $bugReport)
    {
        $bugReport->delete();
        return back()->with('status', 'Report deleted.');
    }
}
