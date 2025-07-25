<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO;

use JaroslawZielinski\FeaturePoll\Model\DTOHandler\StaticCreateSelf;
use JaroslawZielinski\FeaturePoll\Model\DTOHandler\ToArray;
use JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO\FeaturesDTO\QuestionsDTO;

class FeaturesDTO
{
    use StaticCreateSelf;

    use ToArray;

    /**
     * @var string
     */
    public $featureId = null;

    /**
     * @var string
     */
    public $groupName = null;

    /**
     * @var string
     */
    public $description;

    /**
     * @var QuestionsDTO[]
     */
    public $questions;
}
