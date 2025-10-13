<?php

namespace Modules\Ad\Http\Requests\Concerns;

use Illuminate\Http\UploadedFile;

trait NormalizesImageUploads
{
    protected function normalizeImagesPayload(): void
    {
        $imagesInput = $this->input('images');
        $filesBag = $this->file('images');

        if ($imagesInput === null && $filesBag === null) {
            return;
        }

        $normalized = [];
        $sources = is_array($imagesInput) ? $imagesInput : [];

        if (is_array($filesBag)) {
            $files = [];

            foreach ($filesBag as $key => $value) {
                if ($value instanceof UploadedFile) {
                    $files[$key] = $value;

                    continue;
                }

                if (is_array($value) && ($value['file'] ?? null) instanceof UploadedFile) {
                    $files[$key] = $value['file'];
                }
            }
        } elseif ($filesBag instanceof UploadedFile) {
            $files = [$filesBag];
        } else {
            $files = [];
        }

        if ($imagesInput === null && $files !== []) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $normalized[] = ['file' => $file];
                }
            }

            $this->merge(['images' => $normalized]);

            return;
        }

        foreach ($sources as $index => $image) {
            if ($image instanceof UploadedFile) {
                $normalized[] = ['file' => $image];

                continue;
            }

            if (! is_array($image)) {
                $normalized[] = $image;

                continue;
            }

            $payload = $image;

            if (! array_key_exists('file', $payload)) {
                $file = $this->file("images.$index.file");

                if ($file instanceof UploadedFile) {
                    $payload['file'] = $file;
                } elseif (($files[$index] ?? null) instanceof UploadedFile) {
                    $payload['file'] = $files[$index];
                }
            }

            $normalized[] = $payload;
        }

        $this->merge(['images' => $normalized]);
    }
}
