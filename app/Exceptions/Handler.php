<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            $message = $exception->getMessage();

            // Danh sách các thông báo từ create() trong ApprovalPolicy
            $customMessages = [
                'Lớp học này đã được giao, không thể đăng ký.',
                'Chỉ có gia sư mới được phép đăng ký nhận lớp.',
                'Hãy cập nhật hồ sơ của bạn để có thể đăng ký nhận lớp nhé!',
                'Hồ sơ của bạn bị từ chối. Hãy cập nhật lại chính xác hồ sơ của bạn để có thể đăng ký nhận lớp nhé!',
                'Hồ sơ của bạn đang chờ được duyệt. Vui lòng đợi trung tâm xét duyệt hồ sơ để có thể đăng ký nhận lớp nhé!',
            ];

            // Nếu thông báo nằm trong danh sách từ create(), trả về nó
            if (in_array($message, $customMessages)) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            // Các trường hợp AuthorizationException khác trả về thông báo mặc định
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập tài nguyên này.',
            ], 403);
        }

        return parent::render($request, $exception);
    }
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
