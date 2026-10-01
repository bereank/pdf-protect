<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PdfProtectionController extends Controller
{
    public function protect(Request $request)
    {

        Log::info('PDF upload debug', [
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),

            'has_file' => $request->hasFile('file'),

            'file' => $request->file('file')
                ? [
                    'valid' => $request->file('file')->isValid(),
                    'error' => $request->file('file')->getError(),
                    'error_message' => $request->file('file')->getErrorMessage(),
                    'size' => $request->file('file')->getSize(),
                    'name' => $request->file('file')->getClientOriginalName(),
                    'mime' => $request->file('file')->getMimeType(),
                ]
                : null,

            'php_limits' => [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'upload_tmp_dir' => ini_get('upload_tmp_dir'),
            ],
        ]);


        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:20480',
            ],

            'resolution' => [
                'nullable',
                'integer',
                'min:72',
                'max:600',
            ],
        ]);

        $resolution = (int) $request->input(
            'resolution',
            300
        );

        $uuid = (string) Str::uuid();

        $directory = storage_path(
            'app/private/pdf-processing/' . $uuid
        );

        $inputPath = $directory . '/input.pdf';
        $rasterizedPath = $directory . '/rasterized.pdf';
        $protectedPath = $directory . '/protected.pdf';

        try {

            /*
            |--------------------------------------------------------------------------
            | Create processing directory
            |--------------------------------------------------------------------------
            */

            if (
                !is_dir($directory)
                && !mkdir($directory, 0700, true)
                && !is_dir($directory)
            ) {
                throw new RuntimeException(
                    'Unable to create processing directory.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Store uploaded PDF
            |--------------------------------------------------------------------------
            */

            $request->file('file')->move(
                $directory,
                'input.pdf'
            );

            /*
            |--------------------------------------------------------------------------
            | STEP 1: Rasterize
            |--------------------------------------------------------------------------
            */

            $ghostscript = Process::timeout(180)->run([
                '/usr/bin/ghostscript',

                '-dSAFER',
                '-dBATCH',
                '-dNOPAUSE',
                '-dQUIET',

                '-sDEVICE=pdfimage24',

                '-r' . $resolution,

                '-dAutoRotatePages=/None',

                '-sOutputFile=' . $rasterizedPath,

                $inputPath,
            ]);

            if ($ghostscript->failed()) {

                Log::error('Ghostscript failed', [
                    'stderr' =>
                        $ghostscript->errorOutput(),
                ]);

                throw new RuntimeException(
                    'PDF rasterization failed.'
                );
            }

            if (
                !file_exists($rasterizedPath)
                || filesize($rasterizedPath) === 0
            ) {
                throw new RuntimeException(
                    'Rasterized PDF was not generated.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | STEP 2: Protect using AES-256
            |--------------------------------------------------------------------------
            */

            $ownerPassword = Str::random(64);

            $qpdf = Process::timeout(120)->run([
                '/usr/bin/qpdf',

                '--encrypt',
                '',
                $ownerPassword,
                '256',

                '--print=full',
                '--modify=none',
                '--extract=n',
                '--annotate=n',
                '--form=n',
                '--assemble=n',

                '--',

                $rasterizedPath,
                $protectedPath,
            ]);

            if ($qpdf->failed()) {

                Log::error('qpdf failed', [
                    'stderr' =>
                        $qpdf->errorOutput(),
                ]);

                throw new RuntimeException(
                    'PDF protection failed.'
                );
            }

            if (
                !file_exists($protectedPath)
                || filesize($protectedPath) === 0
            ) {
                throw new RuntimeException(
                    'Protected PDF was not generated.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Read final PDF
            |--------------------------------------------------------------------------
            */

            $content = file_get_contents(
                $protectedPath
            );

            if ($content === false) {
                throw new RuntimeException(
                    'Unable to read protected PDF.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Clean before responding
            |--------------------------------------------------------------------------
            */

            $this->cleanup($directory);

            /*
            |--------------------------------------------------------------------------
            | Return PDF
            |--------------------------------------------------------------------------
            */

            return response(
                $content,
                200,
                [
                    'Content-Type' =>
                        'application/pdf',

                    'Content-Disposition' =>
                        'inline; filename="protected.pdf"',

                    'Content-Length' =>
                        strlen($content),

                    'Cache-Control' =>
                        'no-store, no-cache, must-revalidate',
                ]
            );

        } catch (Throwable $exception) {

            Log::error(
                'PDF processing failed',
                [
                    'message' =>
                        $exception->getMessage(),
                ]
            );

            $this->cleanup($directory);

            return response()->json([
                'message' =>
                    'Unable to process PDF.',
            ], 500);
        }
    }

    private function cleanup(
        string $directory
    ): void {
        if (!is_dir($directory)) {
            return;
        }

        $files = glob(
            $directory . '/*'
        );

        if ($files !== false) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }

        @rmdir($directory);
    }
}