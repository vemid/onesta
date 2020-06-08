<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * ClientDocuments
 *
 * @ORM\Table(name="client_documents", indexes={@ORM\Index(name="client_id", columns={"client_id"})})
 * @ORM\Entity
 */
class ClientDocument extends Entity
{
    /**
     * @var string
     *
     * @ORM\Column(name="document_name", type="string", length=255, nullable=false)
     */
    private $documentName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Vemid\ProjectOne\Entity\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set documentName.
     *
     * @param string $documentName
     *
     * @return ClientDocument
     */
    public function setDocumentName($documentName): ClientDocument
    {
        $this->documentName = $documentName;

        return $this;
    }

    /**
     * Get documentName.
     *
     * @return string
     */
    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ClientDocument
     */
    public function setCreatedAt($createdAt): ClientDocument
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set client.
     *
     * @param Client|null $client
     *
     * @return ClientDocument
     */
    public function setClient(Client $client = null): ClientDocument
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return Client|null
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
