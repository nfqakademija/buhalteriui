<?php

namespace App\Service;

class ImageSlicer
{
    private $imagePath;
    private $slicesPath;
    private $image;
    
    public function __construct(string $imagePath, string $slicesPath)
    {
        if (!file_exists($imagePath)) {
            echo 'file not exists';
            exit;
        }
        
        $this->image = imagecreatefromjpeg($imagePath);
        if ($this->image === false) {
            echo 'imagecreatefromjpeg error';
            exit;
        }
        
        $this->slicesPath = $slicesPath;
    }
    
    public function run(array $slices): array
    {
        $image_preix = time();
        
        foreach ($slices as &$slice) {
            $resurce = $this->crop($slice['box']);
            $status = imagepng($resurce, $this->slicesPath . $image_preix . '_' . $slice['key'] . '.png');
            imagedestroy($resurce);
            if ($status === false) {
                echo 'imagepng error: ' . $this->slicesPath . $image_preix . '_' . $slice['key'] . '.png';
                exit;
            }
            
            $slice['file_name'] = $image_preix . '_' . $slice['key'] . '.png';
            $slice['file_path'] = $this->slicesPath . $image_preix . '_' . $slice['key'] . '.png';
        }
        unset($slice);
        
        return $slices;
    }
    
    public function crop($box)
    {
        $im2 = imagecrop($this->image, $box);
        if ($im2 === false) {
            echo 'Crop error';
            exit;
        }
        return $im2;
    }
    
    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }
    
    /**
     * @param mixed $imagePath
     */
    public function setImagePath($imagePath): void
    {
        $this->imagePath = $imagePath;
    }
}
