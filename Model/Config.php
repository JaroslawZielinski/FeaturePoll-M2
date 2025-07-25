<?php

declare(strict_types=1);

namespace JaroslawZielinski\FeaturePoll\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const PATH_SETTINGS_GENERAL_ENABLE = 'jaroslawzielinski_featurepoll/general/enable';

    public const PATH_SETTINGS_GENERAL_HTML_ID = 'jaroslawzielinski_featurepoll/general/html_id';

    public const PATH_SETTINGS_GENERAL_POLL_BADGE = 'jaroslawzielinski_featurepoll/general/poll_badge';

    public const PATH_POLLS_ITEMS = 'jaroslawzielinski_featurepoll/polls/items';

    public const PATH_SETTINGS_GENERAL_MENU_ENABLED = 'jaroslawzielinski_featurepoll/general/menu_enabled';

    public const PATH_SETTINGS_GENERAL_RESULTS_ENABLED = 'jaroslawzielinski_featurepoll/general/results_enabled';

    public const PATH_SETTINGS_GENERAL_CUSTOM_STYLES = 'jaroslawzielinski_featurepoll/general/custom_styles';

    public const PATH_SETTINGS_GENERAL_NONVOTINGPERIOD = 'jaroslawzielinski_featurepoll/general/nonvoting_period';

    public const PATH_SETTINGS_GENERAL_RULESANDREGULATIONS = 'jaroslawzielinski_featurepoll/general/rules_and_regulations';

    public const PATH_SETTINGS_GENERAL_GTMEVENTS_NAME1 = 'jaroslawzielinski_featurepoll/gtm_events/name1';

    public const PATH_SETTINGS_GENERAL_GTMEVENTS_NAME2 = 'jaroslawzielinski_featurepoll/gtm_events/name2';

    public const PATH_SETTINGS_GENERAL_GTMEVENTS_NAME3 = 'jaroslawzielinski_featurepoll/gtm_events/name3';


    public const PATH_SETTINGS_GENERAL_GTMEVENTS_NAME4 = 'jaroslawzielinski_featurepoll/gtm_events/name4';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        JsonSerializer $jsonSerializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function isEnable($scopeCode = null): bool
    {
        return $this->scopeConfig
            ->isSetFlag(self::PATH_SETTINGS_GENERAL_ENABLE, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getPaneHtmlId($scopeCode = null): ?string
    {
        $paneHtmlId = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_HTML_ID, ScopeInterface::SCOPE_STORE, $scopeCode);
        return empty($paneHtmlId) ? null : (string)$paneHtmlId;
    }

    public function getPanePollBadge($scopeCode = null): ?string
    {
        $panePollBadge = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_POLL_BADGE, ScopeInterface::SCOPE_STORE, $scopeCode);
        return empty($panePollBadge) ? null : (string)$panePollBadge;
    }

    public function getPanePollsItems($scopeCode = null): ?array
    {
        $serializedFormsItems =
            $this->scopeConfig->getValue(self::PATH_POLLS_ITEMS, ScopeInterface::SCOPE_STORE, $scopeCode) ?? '{}';
        return $this->jsonSerializer->unserialize($serializedFormsItems);
    }

    public function isModuleMenuEnabled($scopeCode = null): bool
    {
        $isEnabled = $this->isEnable();
        return $isEnabled && $this->scopeConfig
                ->isSetFlag(self::PATH_SETTINGS_GENERAL_MENU_ENABLED, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function isModuleResultsEnabled($scopeCode = null): bool
    {
        $isEnabled = $this->isEnable();
        return $isEnabled && $this->scopeConfig
                ->isSetFlag(self::PATH_SETTINGS_GENERAL_RESULTS_ENABLED, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getModuleCustomStyles($scopeCode = null): ?string
    {
        $customStyles = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_CUSTOM_STYLES, ScopeInterface::SCOPE_STORE, $scopeCode);
        return empty($customStyles) ? null : (string)$customStyles;
    }

    public function getSettingsNonVotingPeriod($scopeCode = null): int
    {
        return (int)$this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_NONVOTINGPERIOD, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getSettingsRulesAndRegulations($scopeCode = null): ?string
    {
        $rulesAndRegulations = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_RULESANDREGULATIONS, ScopeInterface::SCOPE_STORE, $scopeCode);
        return !empty($rulesAndRegulations) ? (string)$rulesAndRegulations : null;
    }

    public function getGTMEventsName1($scopeCode = null): ?string
    {
        $name1 = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_GTMEVENTS_NAME1, ScopeInterface::SCOPE_STORE, $scopeCode);
        return !empty($name1) ? (string)$name1 : null;
    }

    public function getGTMEventsName2($scopeCode = null): ?string
    {
        $name2 = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_GTMEVENTS_NAME2, ScopeInterface::SCOPE_STORE, $scopeCode);
        return !empty($name2) ? (string)$name2 : null;
    }

    public function getGTMEventsName3($scopeCode = null): ?string
    {
        $name3 = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_GTMEVENTS_NAME3, ScopeInterface::SCOPE_STORE, $scopeCode);
        return !empty($name3) ? (string)$name3 : null;
    }

    public function getGTMEventsName4($scopeCode = null): ?string
    {
        $name4 = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_GENERAL_GTMEVENTS_NAME4, ScopeInterface::SCOPE_STORE, $scopeCode);
        return !empty($name4) ? (string)$name4 : null;
    }

    public function getSettingsDescription($scopeCode = null): ?string
    {
        return 'To be constructed...';
    }
}
