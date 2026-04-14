<?php

class Upload
{
    public static function image($file, $dest)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($ext), $allowed)) {
            return null;
        }

        $filename = uniqid('item_') . '.' . $ext;
        $target = rtrim($dest, '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return null;
        }

        return $filename;
    }
}
