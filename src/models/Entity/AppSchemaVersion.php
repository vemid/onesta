<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vemid\ProjectOne\Entity\Entity;

/**
 * AppSchemaVersions
 *
 * @ORM\Table(name="app_schema_versions")
 * @ORM\Entity
 */
class AppSchemaVersion extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(name="version", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $version;

    /**
     * @var string|null
     *
     * @ORM\Column(name="migration_name", type="string", length=100, nullable=true)
     */
    private $migrationName;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="breakpoint", type="boolean", nullable=false)
     */
    private $breakpoint = '0';


    /**
     * Get version.
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set migrationName.
     *
     * @param string|null $migrationName
     *
     * @return AppSchemaVersion
     */
    public function setMigrationName($migrationName = null)
    {
        $this->migrationName = $migrationName;

        return $this;
    }

    /**
     * Get migrationName.
     *
     * @return string|null
     */
    public function getMigrationName()
    {
        return $this->migrationName;
    }

    /**
     * Set startTime.
     *
     * @param \DateTime|null $startTime
     *
     * @return AppSchemaVersion
     */
    public function setStartTime($startTime = null)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime.
     *
     * @return \DateTime|null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime.
     *
     * @param \DateTime|null $endTime
     *
     * @return AppSchemaVersion
     */
    public function setEndTime($endTime = null)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime.
     *
     * @return \DateTime|null
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set breakpoint.
     *
     * @param bool $breakpoint
     *
     * @return AppSchemaVersion
     */
    public function setBreakpoint($breakpoint)
    {
        $this->breakpoint = $breakpoint;

        return $this;
    }

    /**
     * Get breakpoint.
     *
     * @return bool
     */
    public function getBreakpoint()
    {
        return $this->breakpoint;
    }
}
