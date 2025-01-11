<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait HandleImageTrait
{
    /**
     * Xử lý upload ảnh và lưu vào thư mục
     * Xử lý delete ảnh và xóa khỏi thư mục
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string|null
     */
    public function uploadImage($file, $folder = 'images')
    {
        if (!$file) {
            return null; // Trường hợp không có file
        }

        $originalName = Str::slug(
            pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            )
        ); // Lấy tên file gốc

        $extension = $file->getClientOriginalExtension(); // Lấy phần mở rộng của file (jpg, png...)

        // Tạo tên file mới
        $newFileName = Carbon::now()->format('Ymd_His') . '_' . $originalName . '.' . $extension;

        // Lưu file vào thư mục trong 'public' disk
        $filePath = $file->storeAs($folder, $newFileName, 'public');

        return $filePath;
    }

    public function deleteImage($filePath)
    {
        if (Storage::exists('public/' . $filePath)) {
            // Xóa file khỏi storage
            return Storage::delete('public/' . $filePath);
        }

        // Nếu file không tồn tại, trả về false
        return false;
    }
}
