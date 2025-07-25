<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model\Poll\CreatePollDTO\PollDTO\FeaturesDTO;

use JaroslawZielinski\FeaturePoll\Model\DTOHandler\StaticCreateSelf;
use JaroslawZielinski\FeaturePoll\Model\DTOHandler\ToArray;

class QuestionsDTO
{
    use StaticCreateSelf;

    use ToArray;

    /**
     * @var int
     */
    public $position = null;

    /**
     * @var string`
     */
    public $question = null;

    /**
     * @var string
     */
    public $description = null;

    /**
     * @var int
     */
    public $type = null;
}
