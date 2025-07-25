<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Ui\Component\Listing\Column\Features;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Questions extends Column
{
    public const COLUMN_FORMS_QUESTIONS = 'questions';

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @inheritDoc
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[self::COLUMN_FORMS_QUESTIONS])) {
                    $item[self::COLUMN_FORMS_QUESTIONS] = $this->getQuestionsHtml($item);
                }
            }
        }

        return $dataSource;
    }

    private function getQuestionsHtml(array $item): string
    {
        $questions = $item['questions'] ?? [];
        $questionsArray = $this->jsonSerializer->unserialize($questions);
        $htmlArray = [];
        foreach ($questionsArray as $i => $question) {
            $questionHtml = sprintf('%s. %s', $i + 1, $question['question']);
            $htmlArray[] = sprintf(
                '<a href="javascript:void(0)" class="disabled-link" title="%s">%s</a>',
                $question['description'],
                $questionHtml
            );
        }
        $count = count($htmlArray);
        $html = sprintf('%s', implode('<br />', $htmlArray));
        $html= rtrim($html,'<br />');
        return $count > 0 ? $html : 'none';
    }
}
