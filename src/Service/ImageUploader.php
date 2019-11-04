<?php
namespace App\Service;
use Impulze\Bundle\InterventionImageBundle\ImageManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $targetDirectory;
    private $thumbTargetDirectory;
    private $manager;

    public function __construct($targetDirectory, $thumbTargetDirectory, ImageManager $manager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->thumbTargetDirectory = $thumbTargetDirectory;
        $this->manager = $manager;
    }


    public function upload(UploadedFile $file)
    {
        $manager = $this->manager;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $manager->make($file)->save($this->getTargetDirectory() .'/'. $fileName);
            $manager->make($file)->resize(40, 40)->save($this->getThumbTargetDirectory().'/'.$fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function getThumbTargetDirectory()
    {
        return $this->thumbTargetDirectory;
    }
}