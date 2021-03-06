<?php
namespace Ttree\ContentRepositoryImporter\Domain\Model;

use Ttree\ContentRepositoryImporter\Exception\InvalidArgumentException;
use Neos\Flow\Annotations as Flow;

/**
 * Preset Part Definition
 */
class PresetPartDefinition
{
    /**
     * @var string
     */
    protected $currentPresetName;

    /**
     * @var string
     */
    protected $currentPartName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $currentImportIdentifier;

    /**
     * @var string
     */
    protected $dataProviderClassName;

    /**
     * @var array
     */
    protected $dataProviderOptions;

    /**
     * @var string
     */
    protected $importerClassName;

    /**
     * @var integer
     */
    protected $currentBatch;

    /**
     * @var integer
     */
    protected $batchSize;

    /**
     * @var integer
     */
    protected $offset;

    /**
     * @var boolean
     */
    protected $debug = false;

    /**
     * @param array $setting
     * @param string $currentImportIdentifier
     * @throws InvalidArgumentException
     */
    public function __construct(array $setting, $currentImportIdentifier)
    {
        if (!isset($setting['__currentPresetName'])) {
            throw new InvalidArgumentException('Missing or invalid "__currentPresetName" in preset part settings', 1426156156);
        }
        $this->currentPresetName = trim($setting['__currentPresetName']);
        if (!isset($setting['__currentPartName'])) {
            throw new InvalidArgumentException('Missing or invalid "__currentPartName" in preset part settings', 1426156155);
        }
        $this->currentPartName = trim($setting['__currentPartName']);
        if (!isset($setting['label']) || !is_string($setting['label'])) {
            throw new InvalidArgumentException('Missing or invalid "Label" in preset part settings', 1426156157);
        }
        $this->label = (string)$setting['label'];
        if (!isset($setting['dataProviderClassName']) || !is_string($setting['dataProviderClassName'])) {
            throw new InvalidArgumentException('Missing or invalid "dataProviderClassName" in preset part settings', 1426156158);
        }
        $this->dataProviderClassName = (string)$setting['dataProviderClassName'];
        if (!isset($setting['importerClassName']) || !is_string($setting['importerClassName'])) {
            throw new InvalidArgumentException('Missing or invalid "importerClassName" in preset part settings', 1426156159);
        }
        $this->importerClassName = (string)$setting['importerClassName'];
        $this->batchSize = isset($setting['batchSize']) ? (integer)$setting['batchSize'] : null;
        $this->offset = isset($setting['batchSize']) ? 0 : null;
        $this->dataProviderOptions = isset($setting['dataProviderOptions']) ? $setting['dataProviderOptions'] : [];
        $this->currentBatch = 1;
        $this->currentImportIdentifier = $currentImportIdentifier;
        $this->debug = (isset($setting['debug']) && $setting['debug'] === true) ? (boolean)$setting['debug'] : false;
        if ($this->debug === true) {
            $this->batchSize = 1;
        }
    }

    /**
     * Increment the batch number
     */
    public function nextBatch()
    {
        ++$this->currentBatch;
        $this->offset += $this->batchSize;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return sprintf('Preset%s:%s', ucfirst($this->currentPresetName), ucfirst($this->currentPartName));
    }

    /**
     * @return array
     */
    public function getCommandArguments()
    {
        $arguments = [
            'presetName' => $this->currentPresetName,
            'partName' => $this->currentPartName,
            'currentImportIdentifier' => $this->currentImportIdentifier,
            'dataProviderClassName' => $this->dataProviderClassName,
            'importerClassName' => $this->importerClassName,
            'currentBatch' => $this->currentBatch
        ];
        if ($this->batchSize) {
            $arguments['batchSize'] = (integer)$this->batchSize;
        } else {
            $arguments['batchSize'] = 100000;
        }
        if ($this->offset) {
            $arguments['offset'] = (integer)$this->offset;
        }
        return $arguments;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @return string
     */
    public function getCurrentImportIdentifier()
    {
        return $this->currentImportIdentifier;
    }

    /**
     * @return string
     */
    public function getDataProviderClassName()
    {
        return $this->dataProviderClassName;
    }

    /**
     * @return string
     */
    public function getImporterClassName()
    {
        return $this->importerClassName;
    }

    /**
     * @return int
     */
    public function getCurrentBatch()
    {
        return $this->currentBatch;
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
