<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 05/03/19
 * Time: 16:23
 */

namespace App\Service;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Vich\UploaderBundle\VichUploaderBundle;


class IndexImageManualyUploadedService
{

    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexImage()
    {
        $fileSystem = new Filesystem();

        $finder = new Finder();
        $finder->in('public/uploads/images/manual')->files();

        foreach ($finder as $file) {

            $filename = $file->getBasename();

            $newFilename = str_replace('.', '', uniqid('', true)) . '.' . $file->getExtension();
            $fileSystem->copy('public/uploads/images/manual/'.$filename, 'public/uploads/images/'.$newFilename);

            $image = new Image();
            $image->setImage($newFilename);
            $image->setImageFile($image);
            $this->entityManager->persist($image);

            $fileSystem->remove(['public/uploads/images/manual/'.$filename]);

        }

        $this->entityManager->flush();
    }


}