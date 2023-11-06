<?php

namespace App\Classes;

use App\Exceptions\ApiException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class Files
{
    public const USER_UPLOAD_FOLDER = 'user-uploads';
    public const CLIENT_UPLOAD_FOLDER = 'client-uploads';
    public const CLIENT_LOGO_FOLDER = 'client-logo';
    public const USER_AVATAR_FOLDER ='user-avatar';
    public const DEFAULT_IMAGE_HEIGHT = 800;
    public const HELP_CALL_FOLDER = 'help-call';

    /**
     * @throws ApiException
     */
    public static function upload(
        UploadedFile $file,
        string $dir,
        $width = null,
        int $height = self::DEFAULT_IMAGE_HEIGHT,
        $options = []
    ): string
    {
        self::validateUploadedFile($file);
        $newName = self::generateNewFileName($file->getClientOriginalName());
        $folderType = (array_key_exists('isUser', $options) && $options['isUser'])
            ? self::USER_UPLOAD_FOLDER
            : self::CLIENT_UPLOAD_FOLDER;

        $path = public_path("$folderType/$dir/$newName");
        self::createDirectoryIfNotExist($dir, $folderType);

        $file->move(public_path("$folderType/$dir"), $newName);

        if ($width && $height && $file->getClientOriginalExtension() !== 'svg') {
            self::resizeImage($path, $width, $height);
        }

        return $newName;
    }

    /**
     * @throws ApiException
     */
    public static function validateUploadedFile($uploadedFile): void
    {
        if (!$uploadedFile->isValid()) {
            throw new ApiException('File was not uploaded correctly');
        }

        if ($uploadedFile->getClientOriginalExtension() === 'php' || $uploadedFile->getMimeType() === 'text/x-php') {
            throw new ApiException('You are not allowed to upload the php file on server', null, 422, 422, 2023);
        }

        if (
            $uploadedFile->getClientOriginalExtension() === 'sh'
            || $uploadedFile->getMimeType() === 'text/x-shellscript'
        ) {
            throw new ApiException(
                'You are not allowed to upload the shell script file on server',
                null,
                422,
                422,
                2023
            );
        }

        if ($uploadedFile->getClientOriginalExtension() === 'htaccess') {
            throw new ApiException(
                'You are not allowed to upload the htaccess file on server',
                null,
                422,
                422,
                2023
            );
        }

        if ($uploadedFile->getClientOriginalExtension() === 'xml') {
            throw new ApiException(
                'You are not allowed to upload XML FILE',
                null,
                422,
                422,
                2023
            );
        }

        if ($uploadedFile->getSize() <= 10) {
            throw new ApiException(
                'You are not allowed to upload a file with filesize less than 10 bytes',
                null,
                422,
                422,
                2023
            );
        }
    }

    public static function generateNewFileName($currentFileName): string
    {
        $ext = strtolower(File::extension($currentFileName));
        $newName = md5(microtime());

        return ($ext === '') ? $newName : $newName . '.' . $ext;
    }

    public static function createDirectoryIfNotExist($folder, $folderType): void
    {
        if (!File::exists(public_path($folderType . '/' . $folder))) {
            File::makeDirectory(public_path($folderType . '/' . $folder), 0775, true);
        }
    }

    private static function resizeImage(string $filePath, int $width, int $height): void
    {
        Image::make($filePath)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save();
    }

    public static function getImageUrl($fileName, $folder, $folderType = self::USER_UPLOAD_FOLDER): string
    {
        if ($fileName === null) {
            return '';
        }
        return asset("$folderType/$folder/$fileName");
    }

    public static function uploadMultipleFiles(
        $files,
        $dir,
        $width = null,
        $height = self::DEFAULT_IMAGE_HEIGHT,
        $options = []
    ): array
    {
        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = self::upload($file, $dir, $width, $height, $options);
        }
        return $fileNames;
    }
}
