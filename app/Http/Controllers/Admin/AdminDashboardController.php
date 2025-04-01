<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class1;
use App\Models\Parent1;
use App\Models\Report;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function getStats()
    {
        $totalParents = Parent1::count();
        $totalTutors = Tutor::count();
        $totalClasses = Class1::count();

        $classesWithTutors = Class1::whereNotNull('tutor_id')->count();
        $matchingRate = $totalClasses > 0 ? round(($classesWithTutors / $totalClasses) * 100, 2) : 0;

        $pendingTutors = Tutor::where('profile_status', 0)->count();
        $pendingClasses = Class1::where('status', 0)->count();
        $pendingReports = Report::where('status', 0)->count();

        // Thống kê đăng ký theo tháng trong năm hiện tại
        $currentYear = date('Y');
        $monthlyRegistrations = DB::table('users')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('role', 'parent')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $tutorRegistrations = DB::table('users')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('role', 'tutor')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $classRegistrations = DB::table('classes')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Thống kê lớp học theo trạng thái
        $classesByStatus = Class1::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->total];
            })
            ->toArray();

        // Đảm bảo có đầy đủ các trạng thái, ngay cả khi không có dữ liệu
        $classStatusData = [
            '-1' => $classesByStatus['-1'] ?? 0, // Đã hủy
            '0' => $classesByStatus['0'] ?? 0,   // Chưa giao (pending)
            '1' => $classesByStatus['1'] ?? 0,   // Đã giao
            '2' => $classesByStatus['2'] ?? 0    // Đã kết thúc
        ];

        // Lấy 5 lớp học chưa được ghép mới nhất
        $recentPendingClasses = Class1::where('status', 0)
            ->orWhereNull('tutor_id')
            ->with([
                'parent:id,user_id',
                'parent.user:id,name',
                'subjects',
                'grade',
            ])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Lấy 5 gia sư đăng ký mới nhất chưa được duyệt
        $recentPendingTutors = Tutor::where('profile_status', 0)
            ->with([
                'user:id,name',
            ])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return response()->json(
            [
                'success' => true,
                'data' => [
                    'totalParents' => $totalParents,
                    'totalTutors' => $totalTutors,
                    'totalClasses' => $totalClasses,
                    'matchingRate' => $matchingRate,
                    'pendingTutors' => $pendingTutors,
                    'pendingClasses' => $pendingClasses,
                    'pendingReports' => $pendingReports,
                    'monthlyRegistrations' => [
                        'parents' => $monthlyRegistrations,
                        'tutors' => $tutorRegistrations,
                        'classes' => $classRegistrations
                    ],
                    'classesByStatus' => $classStatusData,
                    'recentPendingClasses' => $recentPendingClasses,
                    'recentPendingTutors' => $recentPendingTutors
                ]
            ]
        );
    }
}
