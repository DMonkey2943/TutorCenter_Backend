<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo nhận lớp thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            color: #333;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 10px;
            border-left: 5px solid #007bff;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>🎉 Chúc mừng, bạn đã nhận lớp học mới thành công! 🎉</h2>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $tutor['name'] }}</strong>,</p>

            <p>Bạn đã được xác nhận là gia sư cho lớp học mã số {{ $classInfo['id'] }} sau:</p>

            <div class="info-box">
                <p><strong>📖 Môn dạy:</strong> {{ implode(', ', $classInfo['subjects']) }}</p>
                <p><strong>📚 Khối lớp dạy:</strong> {{ $classInfo['grade'] }}</p>
                <p><strong>📍 Địa chỉ:</strong> {{ $classInfo['address'] }}</p>
                @if ($classInfo['tuition'] == 'Thỏa thuận')
                    <p><strong>💰 Học phí/buổi:</strong> Thỏa thuận</p>
                @else
                    <p><strong>💰 Học phí/buổi:</strong> {{ number_format($classInfo['tuition'], 0, ',', '.') }} VNĐ</p>
                @endif
            </div>

            <h3>🕒 Thời gian dạy:</h3>
            <ul>
                @foreach ($classInfo['classTimes'] as $time)
                    <li><strong>{{ $time['day'] }}</strong>: {{ $time['start'] }} - {{ $time['end'] }}</li>
                @endforeach
            </ul>

            <h3>📞 Thông tin Phụ huynh:</h3>
            <p><strong>👨‍👩‍👦 Họ tên:</strong> {{ $parentInfo['name'] }}</p>
            <p><strong>📱 Số điện thoại:</strong> {{ $parentInfo['phone'] }}</p>

            <p>Hãy liên hệ với phụ huynh sớm nhất để bắt đầu công việc giảng dạy.</p>

            {{-- <p>
            <a href="{{ url('/classes/' . $classInfo['id']) }}" class="button">🔍 Xem chi tiết lớp học</a>
        </p> --}}

            <p>Chúc bạn có một buổi dạy hiệu quả và thành công!</p>
        </div>

        <div class="footer">
            <p>📧 Gia Sư Cần Thơ</p>
        </div>
    </div>

</body>

</html>
