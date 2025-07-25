<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO;

use JaroslawZielinski\FeaturePoll\Model\DTOHandler\StaticCreateSelf;
use JaroslawZielinski\FeaturePoll\Model\DTOHandler\ToArray;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO\FeaturesDTO;

class PollDTO
{
    use StaticCreateSelf;

    use ToArray;

    /**
     * @var int
     */
    public $pollId = null;

    /**
     * @var string
     */
    public $groupName = null;

    /**
     * @var string
     */
    public $description = null;

    /**
     * @var string
     */
    public $dateFrom = null;

    /**
     * @var string
     */
    public $dateTo = null;

    /**
     * @var FeaturesDTO[]
     */
    public $features;
}
