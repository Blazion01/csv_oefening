<?php

namespace App\Entity;

use App\Repository\CsvFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CsvFileRepository::class)
 * @Vich\Uploadable
 */
class CsvFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
    * NOTE: This is not a mapped field of entity metadata, just a simple property.
    * 
    * @Vich\UploadableField(mapping="csv_files", fileNameProperty="csvName", size="csvSize")
    * 
    * @var File|null
    */
   private $csvFile;

   /**
    * @ORM\Column(type="string")
    *
    * @var string|null
    */
   private $csvName;

   /**
    * @ORM\Column(type="integer")
    *
    * @var int|null
    */
   private $csvSize;

   /**
    * @ORM\Column(type="datetime")
    *
    * @var \DateTimeInterface|null
    */
   private $updatedAt;

   public function getId(): ?int
   {
       return $this->id;
   }

   /**
    * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
    * of 'UploadedFile' is injected into this setter to trigger the update. If this
    * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
    * must be able to accept an instance of 'File' as the bundle will inject one here
    * during Doctrine hydration.
    *
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $csvFile
    */
   public function setCsvFile(?File $csvFile = null): void
   {
       $this->csvFile = $csvFile;

       if (null !== $csvFile) {
           // It is required that at least one field changes if you are using doctrine
           // otherwise the event listeners won't be called and the file is lost
           $this->updatedAt = new \DateTimeImmutable();
       }
   }

   public function getCsvFile(): ?File
   {
       return $this->csvFile;
   }

   public function setCsvName(?string $csvName): void
   {
       $this->csvName = $csvName;
   }

   public function getCsvName(): ?string
   {
       return $this->csvName;
   }
   
   public function setCsvSize(?int $csvSize): void
   {
       $this->csvSize = $csvSize;
   }

   public function getCsvSize(): ?int
   {
       return $this->csvSize;
   }
}
