<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BirthdayController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->input('month', date('m'));
        // Get the current month
//        $currentMonth = date('m');

        // Fetch members with the same birth month
        $members = Member::whereMonth('dob', $currentMonth)->get();

        return view('birthday', compact('members'));
    }

    /**
     * Test cron job manually via web route
     */
    public function testCron(Request $request)
    {
        try {
            $message = $request->get('message', 'Manual web test');

            // Method 1: Using Artisan facade (runs in same process)
            $exitCode = Artisan::call('command:reminders', [
                '--message' => $message
            ]);

            $output = Artisan::output();

            return response()->json([
                'status' => 'success',
                'message' => 'Cron job executed successfully',
                'exit_code' => $exitCode,
                'output' => $output,
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cron job failed',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Test cron job using background process (more realistic)
     */
    public function testCronBackground(Request $request)
    {
        try {
            $message = $request->get('message', 'Background process test');

            // Method 2: Using background process (more like real cron)
            $command = [
                'php',
                base_path('artisan'),
                'command:reminders',
                '--message=' . $message
            ];

            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Background cron job executed successfully',
                'output' => $process->getOutput(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Background cron job failed',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Test Laravel scheduler
     */
    public function testScheduler()
    {
        try {
            // Run the scheduler once
            $exitCode = Artisan::call('schedule:run');
            $output = Artisan::output();

            return response()->json([
                'status' => 'success',
                'message' => 'Scheduler executed successfully',
                'exit_code' => $exitCode,
                'output' => $output,
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Scheduler failed',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Get cron job logs
     */
    public function getCronLogs()
    {
        $cronLogPath = storage_path('logs/cron-test.log');
        $outputLogPath = storage_path('logs/cron-output.log');

        $logs = [];

        // Read cron test logs
        if (file_exists($cronLogPath)) {
            $logs['cron_test'] = array_slice(file($cronLogPath, FILE_IGNORE_NEW_LINES), -20); // Last 20 lines
        }

        // Read cron output logs
        if (file_exists($outputLogPath)) {
            $logs['cron_output'] = array_slice(file($outputLogPath, FILE_IGNORE_NEW_LINES), -20); // Last 20 lines
        }

        return response()->json([
            'status' => 'success',
            'logs' => $logs,
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if cron is working (by checking recent log entries)
     */
    public function checkCronStatus()
    {
        $cronLogPath = storage_path('logs/cron-test.log');

        if (!file_exists($cronLogPath)) {
            return response()->json([
                'status' => 'warning',
                'message' => 'No cron logs found',
                'is_working' => false
            ]);
        }

        $lastModified = filemtime($cronLogPath);
        $minutesAgo = (time() - $lastModified) / 60;

        // Consider cron working if last execution was within 5 minutes
        $isWorking = $minutesAgo <= 5;

        return response()->json([
            'status' => 'success',
            'is_working' => $isWorking,
            'last_execution' => Carbon::createFromTimestamp($lastModified)->format('Y-m-d H:i:s'),
            'minutes_ago' => round($minutesAgo, 2),
            'message' => $isWorking ? 'Cron is working' : 'Cron may not be working properly'
        ]);
    }

    /**
     * Clear cron logs
     */
    public function clearCronLogs()
    {
        $cronLogPath = storage_path('logs/cron-test.log');
        $outputLogPath = storage_path('logs/cron-output.log');

        $cleared = [];

        if (file_exists($cronLogPath)) {
            unlink($cronLogPath);
            $cleared[] = 'cron-test.log';
        }

        if (file_exists($outputLogPath)) {
            unlink($outputLogPath);
            $cleared[] = 'cron-output.log';
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logs cleared successfully',
            'cleared_files' => $cleared,
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
