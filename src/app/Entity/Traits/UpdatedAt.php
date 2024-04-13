<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait UpdatedAt
{
    #[PrePersist, PreUpdate]
	public function makeUpdatedAt()
	{
        $this->updatedAt = new DateTime();
	}
}