<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
#[Table(name: "blob_test")]
#[Entity]
class BlobTest
{
    #[Id]
    #[Column(name: "name", length: 50, nullable: false)]
    private string $name;

    #[Column(name: "content_type", length: 50, nullable: false)]
    private string $contentType;

    #[Column(name: "blob_contents", type: "blob", length: 0, nullable: false)]
    private $blobContents;

    public function setName(string $name): Blobtest {
        $this->name = $name;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setContentType(string $contentType): self {
        $this->contentType = $contentType;
        return $this;
    }

    public function getContentType(): string {
        return $this->contentType;
    }

    public function setBlobContents($blobContents): self {
        $this->blobContents = $blobContents;
        return $this;
    }

    public function getBlobContents() {
        return $this->blobContents;
    }
}