<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use JaroslawZielinski\FeaturePoll\Model\VoteFixtures\Console as VoteFixturesConsole;

class VoteFixtures extends Command
{
    public const VOTES_COUNT = 'voter-count';

    public const VOTES_POLL_ID = 'voter-poll-id';

    public const VOTES_IF_CLEAR = 'voter-if-clear';

    /**
     * @var array
     */
    private $messages;

    /**
     * @var VoteFixturesConsole
     */
    private $voteFixturesConsole;

    /**
     * @var ProgressBar
     */
    private $progressBar;

    private $output;

    /**
     * @inheritDoc
     */
    public function __construct(
        VoteFixturesConsole $voteFixturesConsole
    ) {
        $this->messages = [];
        $this->voteFixturesConsole = $voteFixturesConsole;
        parent::__construct($name = null);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('featurepoll:vote-fixtures:create');
        $this->setDescription('FeaturePoll Vote Fixtures');
        $this->addArgument(self::VOTES_POLL_ID, InputArgument::REQUIRED, (string)__('poll ID'));
        $this->addArgument(self::VOTES_COUNT, InputArgument::REQUIRED, (string)__('votes count'));
        $this->addOption(self::VOTES_IF_CLEAR,'c', $mode = InputOption::VALUE_REQUIRED, (string)__('clear votes.'), $default = true);
    }

    private function displayMessages(OutputInterface $output): int
    {
        foreach ($this->messages as $message) {
            $output->writeln($message);
        }
        return 1;
    }

    private function addMessage(string $message, ...$args): void
    {
        $this->messages[] = sprintf($message, ...$args);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $surveyId = (int)$input->getArgument(self::VOTES_POLL_ID) ?? null;
        $voteCount = (int)$input->getArgument(self::VOTES_COUNT) ?? null;
        $ifClear = !!$input->getOption(self::VOTES_IF_CLEAR);

        if ($surveyId > 0 && $voteCount > 0) {
            $this->voteFixturesConsole->execute(
                $surveyId,
                $voteCount,
                $ifClear,
                [$this, 'start'],
                [$this, 'iteration'],
                [$this, 'end']
            );
        } else {
            throw new \Exception(sprintf((string)__('Wrong parameters.')));
        }
        return $this->displayMessages($output);
    }

    public function start(int $max): void
    {
        $consoleOutput = new ConsoleOutput();
        $this->progressBar = new ProgressBar($consoleOutput, $max);
        $format = 'very_verbose';
        $this->progressBar->setFormat($format);
        // Start the progress bar
        $this->progressBar->start();
    }

    public function iteration(): void
    {
        $this->progressBar->advance();
    }

    public function end(): void
    {
        $this->progressBar->finish();
        $this->output->writeln('');
    }
}
