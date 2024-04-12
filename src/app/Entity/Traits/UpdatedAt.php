<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping\PrePersist;

trait UpdatedAt
{
    #[PrePersist]
	public function makeUpdatedAt()
	{
        $this->updatedAt = new DateTime(date('d-m-Y h:i A'));
        var_dump($this->updatedAt);
	}
}