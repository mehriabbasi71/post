<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadFile {

    private $file;
    private $uploadPath;

    /**
     * set the value of file
     *
     * @param $file
     * @return self
     */
    public function setFile(?UploadedFile $file) {

        $this->file = $file;

        return $this;
    }

    /**
     * set the value of uploadPath
     *
     * @param $uploadPath
     * @return self
     */
    public function setUploadPath(string $uploadPath) {

        $this->uploadPath = $uploadPath;

        return $this;
    }

    public function execute(): ?string {
        if(!$this->file) {
            return null;
        }

        $imageName = (string) Str::of($this->file->getClientOriginalName())
            ->before('.')
            ->slug()
            ->append('.')
            ->append($this->file->getClientOriginalExtension());

        $this->file->storeAs($this->uploadPath,$imageName);

        return $imageName;
    }
}
