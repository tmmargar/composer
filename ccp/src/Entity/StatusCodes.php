<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_status_codes")]
#[Entity(repositoryClass: StatusCodesRepository::class)]
class StatusCodes
{
    #[Column(name: "status_code", length: 1, nullable: false)]
    #[Id]
    private string $statusCode;

    #[Column(name: "status_code_name", length: 20, nullable: false)]
    private string $statusCodeName;

    #[OneToMany(targetEntity: Results::class, mappedBy: "statusCodes")]
    #[JoinColumn(name: "status_code", referencedColumnName: "status_code")]
    private Collection $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getStatusCode(): string {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getStatusCodeName(): string {
        return $this->statusCodeName;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults(): Collection {
        return $this->results;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode(string $statusCode): self {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $statusCodeName
     */
    public function setStatusCodeName(string $statusCodeName): self {
        $this->statusCodeName = $statusCodeName;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $results
     */
    public function setResults(Collection $results): self {
        $this->results = $results;
        return $this;
    }

    public function addResults(Results $results): self
    {
        $this->results[] = $results;
        return $this;
    }

    public function removeResults(Results $results): self
    {
        $this->results->removeElement($results);
        return $this;
    }
}