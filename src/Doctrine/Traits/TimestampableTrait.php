<?php declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false, options: ['default' => 'CURRENT_TIMESTAMP'], generated: 'INSERT')]
    #[ApiProperty(writable: false, readable: true)]
    public ?DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, insertable: false, updatable: false, columnDefinition: 'DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP', generated: 'ALWAYS')]
    #[ApiProperty(writable: false, readable: true)]
    public ?DateTime $updatedAt = null;
}
