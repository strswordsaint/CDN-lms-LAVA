<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master User Report - <?php echo date('Y-m-d'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 0.5cm; size: landscape; }
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .shadow-sm { box-shadow: none !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000 !important; padding: 8px !important; font-size: 12px !important; }
            th { background-color: #f3f4f6 !important; font-weight: bold; }
            /* Ensure status colors print */
            .text-green-700 { color: #15803d !important; }
            .text-red-700 { color: #b91c1c !important; }
            .text-amber-700 { color: #b45309 !important; }
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; color: #111; }
    </style>
</head>
<body class="p-8" onload="window.print()">

    <div class="no-print fixed top-0 left-0 w-full bg-white shadow-md p-4 flex justify-between items-center z-50">
        <h2 class="text-lg font-bold text-gray-700">Print Preview</h2>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-bold text-sm">
                Print Now
            </button>
            <button onclick="window.close()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow font-bold text-sm">
                Close
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto mt-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Colegio de Naujan LMS</h1>
            <h2 class="text-xl font-semibold text-gray-600 mt-1"><?php echo $report_title ?? 'User Report'; ?></h2>
            <p class="text-sm text-gray-500 mt-2">Generated on: <?php echo date('F j, Y \a\t h:i A'); ?></p>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-6 text-sm">
            <div class="p-3 border border-gray-300 rounded bg-white">
                <span class="block text-gray-500 font-bold uppercase text-xs">Total Users</span>
                <span class="text-xl font-bold"><?php echo count($users); ?></span>
            </div>
            <div class="p-3 border border-gray-300 rounded bg-white">
                <span class="block text-gray-500 font-bold uppercase text-xs">Students</span>
                <span class="text-xl font-bold text-green-600">
                    <?php echo count(array_filter($users, fn($u) => $u['role'] == 'student')); ?>
                </span>
            </div>
            <div class="p-3 border border-gray-300 rounded bg-white">
                <span class="block text-gray-500 font-bold uppercase text-xs">Teachers</span>
                <span class="text-xl font-bold text-amber-600">
                    <?php echo count(array_filter($users, fn($u) => $u['role'] == 'teacher')); ?>
                </span>
            </div>
             <div class="p-3 border border-gray-300 rounded bg-white">
                <span class="block text-gray-500 font-bold uppercase text-xs">Admins</span>
                <span class="text-xl font-bold text-purple-600">
                    <?php echo count(array_filter($users, fn($u) => $u['role'] == 'admin')); ?>
                </span>
            </div>
        </div>

        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="px-4 py-2 font-bold text-gray-700 uppercase text-xs">Full Name</th>
                        <th class="px-4 py-2 font-bold text-gray-700 uppercase text-xs">Email Address</th>
                        <th class="px-4 py-2 font-bold text-gray-700 uppercase text-xs text-center">Role</th>
                        <th class="px-4 py-2 font-bold text-gray-700 uppercase text-xs text-center">Status</th>
                        <th class="px-4 py-2 font-bold text-gray-700 uppercase text-xs text-right">Avg. Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">
                            <?php echo htmlspecialchars($user['last_name'] . ', ' . $user['first_name']); ?>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-600 font-mono">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <span class="px-2 py-0.5 rounded text-xs font-bold border
                                <?php 
                                    if($user['role'] == 'admin') echo 'bg-purple-50 text-purple-700 border-purple-200';
                                    elseif($user['role'] == 'teacher') echo 'bg-amber-50 text-amber-700 border-amber-200';
                                    else echo 'bg-green-50 text-green-700 border-green-200';
                                ?>">
                                <?php echo strtoupper($user['role']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <?php if($user['status'] == 'approved'): ?>
                                <span class="text-green-700 font-bold text-xs">Active</span>
                            <?php elseif($user['status'] == 'suspended'): ?>
                                <span class="text-red-700 font-bold text-xs">Suspended</span>
                            <?php else: ?>
                                <span class="text-amber-700 font-bold text-xs">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2 text-sm text-right font-mono">
                            <?php 
                                if ($user['role'] == 'student') {
                                    if (isset($student_grades[$user['user_id']])) {
                                        $g = $student_grades[$user['user_id']];
                                        // Color code the grades
                                        $color = $g >= 75 ? 'text-green-700' : 'text-red-600';
                                        echo "<span class='font-bold {$color}'>" . number_format($g, 1) . "%</span>";
                                    } else {
                                        echo '<span class="text-gray-400">-</span>';
                                    }
                                } else {
                                    echo '<span class="text-gray-300">N/A</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 text-center text-xs text-gray-400">
            End of Report - Colegio de Naujan Learning Management System
        </div>
    </div>

</body>
</html>