<?php

/**
 * ManifestService.
 */

declare(strict_types = 1);

namespace SFC\Staticfilecache\Service;

use SFC\Staticfilecache\Cache\IdentifierBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ManifestService.
 *
 * For handling the Offline functions
 */
class ManifestService extends AbstractService
{

    /**
     * Generate the manifest file content
     *
     * @param string $filename
     * @param string $data
     * @return string
     */
    public function generateManifestContent(string $filename, string &$data): string
    {

        //DebuggerUtility::var_dump($filename);
        //DebuggerUtility::var_dump($data);
        //die();

        return '';

        //DebuggerUtility::var_dump($fileName);
        //DebuggerUtility::var_dump($entryIdentifier);
        //DebuggerUtility::var_dump($data);
        //die();
    }

    /**
     * Frontend call of the appcache files
     */
    public function callEid()
    {
        header('Content-Type: text/cache-manifest');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: ' . date(DATE_RFC1123));

        try {
            $identifierBuilder = GeneralUtility::makeInstance(IdentifierBuilder::class);
            $fileName = $identifierBuilder->getCacheFilename(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));

            // var_dump($fileName);
        } catch (\Exception $exception) {
            // $this->lo
            //$exception->getMessage()
        }
    }
}
